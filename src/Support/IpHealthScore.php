<?php

namespace Mca\AccessIntel\Support;

final class IpHealthScore
{
    public const LEVEL_HEALTHY = 'healthy';

    public const LEVEL_WATCH = 'watch';

    public const LEVEL_RISKY = 'risky';

    public const LEVEL_UNKNOWN = 'unknown';

    /**
     * @param  array{
     *   hits: int,
     *   errors: int,
     *   blocked: int,
     *   unique_paths: int
     * }  $stats
     * @return array{
     *   score: int,
     *   level: string,
     *   factors: array<string, float|int>
     * }
     */
    public static function evaluate(array $stats): array
    {
        $hits = max(0, (int) ($stats['hits'] ?? 0));
        $errors = max(0, (int) ($stats['errors'] ?? 0));
        $blocked = max(0, (int) ($stats['blocked'] ?? 0));
        $uniquePaths = max(0, (int) ($stats['unique_paths'] ?? 0));

        if ($hits === 0) {
            return [
                'score' => 0,
                'level' => self::LEVEL_UNKNOWN,
                'factors' => [
                    'hits' => 0,
                    'error_ratio' => 0,
                    'blocked_ratio' => 0,
                    'path_diversity' => 0,
                ],
            ];
        }

        $cfg = config('access-intel.scoring', []);
        $errorRatio = $errors / $hits;
        $blockedRatio = $blocked / $hits;
        $pathDiversity = min(1.0, $uniquePaths / $hits);

        $score = 100.0;
        $score -= self::penalty($hits, (int) ($cfg['volume_soft'] ?? 200), (int) ($cfg['volume_hard'] ?? 1000), 25);
        $score -= self::penalty($errorRatio, (float) ($cfg['error_soft'] ?? 0.15), (float) ($cfg['error_hard'] ?? 0.45), 35);
        $score -= self::penalty($pathDiversity, (float) ($cfg['path_diversity_soft'] ?? 0.35), (float) ($cfg['path_diversity_hard'] ?? 0.75), 25);
        $score -= self::penalty($blockedRatio, (float) ($cfg['blocked_soft'] ?? 0.05), (float) ($cfg['blocked_hard'] ?? 0.25), 30);

        $score = (int) max(0, min(100, round($score)));

        $healthyMin = (int) ($cfg['healthy_min'] ?? 70);
        $watchMin = (int) ($cfg['watch_min'] ?? 40);

        $level = match (true) {
            $score >= $healthyMin => self::LEVEL_HEALTHY,
            $score >= $watchMin => self::LEVEL_WATCH,
            default => self::LEVEL_RISKY,
        };

        return [
            'score' => $score,
            'level' => $level,
            'factors' => [
                'hits' => $hits,
                'error_ratio' => round($errorRatio, 4),
                'blocked_ratio' => round($blockedRatio, 4),
                'path_diversity' => round($pathDiversity, 4),
            ],
        ];
    }

    private static function penalty(float|int $value, float|int $soft, float|int $hard, float $maxPenalty): float
    {
        if ($value <= $soft) {
            return 0.0;
        }

        if ($value >= $hard || $hard <= $soft) {
            return $maxPenalty;
        }

        $ratio = ($value - $soft) / ($hard - $soft);

        return $maxPenalty * $ratio;
    }
}
