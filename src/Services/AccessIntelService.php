<?php

namespace Mca\AccessIntel\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Mca\AccessIntel\Support\IpHealthScore;
use Mca\AccessLog\Models\AccessLog;

class AccessIntelService
{
    public function accessLogAvailable(): bool
    {
        return class_exists(AccessLog::class) && function_exists('mca_access_log');
    }

    public function firewallAvailable(): bool
    {
        return function_exists('mca_firewall') && class_exists(\Mca\Firewall\Services\FirewallService::class);
    }

    public function windowStart(): Carbon
    {
        $hours = max(1, (int) config('access-intel.window_hours', 24));

        return now()->subHours($hours);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function rankedIps(?int $limit = null, bool $fresh = false): Collection
    {
        if (! $this->accessLogAvailable()) {
            return collect();
        }

        $limit = $limit ?? (int) config('access-intel.ui.limit', 50);
        $cacheKey = (string) config('access-intel.cache.key', 'mca.access-intel.ranks').'.'.$limit;

        if (! $fresh && config('access-intel.cache.enabled', true)) {
            /** @var list<array<string, mixed>>|null $cached */
            $cached = Cache::get($cacheKey);
            if (is_array($cached)) {
                return collect($cached);
            }
        }

        $since = $this->windowStart();

        $rows = AccessLog::query()
            ->select([
                'ip',
                DB::raw('count(*) as hits'),
                DB::raw('sum(case when status_code >= 400 then 1 else 0 end) as errors'),
                DB::raw('sum(case when is_blocked = 1 then 1 else 0 end) as blocked'),
                DB::raw('count(distinct path) as unique_paths'),
                DB::raw('max(created_at) as last_seen'),
            ])
            ->where('created_at', '>=', $since)
            ->groupBy('ip')
            ->orderByDesc('hits')
            ->limit(max($limit * 3, 50))
            ->get();

        $ranked = $rows->map(function ($row) {
            $eval = IpHealthScore::evaluate([
                'hits' => (int) $row->hits,
                'errors' => (int) $row->errors,
                'blocked' => (int) $row->blocked,
                'unique_paths' => (int) $row->unique_paths,
            ]);

            return [
                'ip' => (string) $row->ip,
                'hits' => (int) $row->hits,
                'errors' => (int) $row->errors,
                'blocked' => (int) $row->blocked,
                'unique_paths' => (int) $row->unique_paths,
                'last_seen' => $row->last_seen,
                'score' => $eval['score'],
                'level' => $eval['level'],
                'factors' => $eval['factors'],
            ];
        })
            ->sortBy([
                ['score', 'asc'],
                ['hits', 'desc'],
            ])
            ->values()
            ->take($limit)
            ->values();

        if (config('access-intel.cache.enabled', true)) {
            Cache::put($cacheKey, $ranked->all(), (int) config('access-intel.cache.ttl', 120));
        }

        return $ranked;
    }

    /** @return array<string, mixed> */
    public function summarizeIp(string $ip): array
    {
        if (! $this->accessLogAvailable()) {
            return [
                'ip' => $ip,
                'available' => false,
                'score' => 0,
                'level' => IpHealthScore::LEVEL_UNKNOWN,
            ];
        }

        $since = $this->windowStart();
        $base = AccessLog::query()->forIp($ip)->where('created_at', '>=', $since);

        $hits = (clone $base)->count();
        $errors = (clone $base)->where('status_code', '>=', 400)->count();
        $blocked = (clone $base)->blocked()->count();
        $uniquePaths = (clone $base)->distinct('path')->count('path');
        $lastSeen = (clone $base)->max('created_at');

        $eval = IpHealthScore::evaluate([
            'hits' => $hits,
            'errors' => $errors,
            'blocked' => $blocked,
            'unique_paths' => $uniquePaths,
        ]);

        return [
            'ip' => $ip,
            'available' => true,
            'window_hours' => (int) config('access-intel.window_hours', 24),
            'hits' => $hits,
            'errors' => $errors,
            'blocked' => $blocked,
            'unique_paths' => $uniquePaths,
            'last_seen' => $lastSeen ? Carbon::parse($lastSeen) : null,
            'score' => $eval['score'],
            'level' => $eval['level'],
            'factors' => $eval['factors'],
        ];
    }

    /** @return array{healthy: int, watch: int, risky: int, total: int} */
    public function overviewCounts(?Collection $ranked = null): array
    {
        $ranked ??= $this->rankedIps();

        return [
            'healthy' => $ranked->where('level', IpHealthScore::LEVEL_HEALTHY)->count(),
            'watch' => $ranked->where('level', IpHealthScore::LEVEL_WATCH)->count(),
            'risky' => $ranked->where('level', IpHealthScore::LEVEL_RISKY)->count(),
            'total' => $ranked->count(),
        ];
    }

    public function forgetCache(): void
    {
        $limit = (int) config('access-intel.ui.limit', 50);
        Cache::forget((string) config('access-intel.cache.key', 'mca.access-intel.ranks').'.'.$limit);
    }
}
