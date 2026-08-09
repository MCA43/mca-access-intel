<?php

namespace Mca\AccessIntel\Http\Controllers\Web;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Mca\AccessIntel\Services\AccessIntelService;
use Mca\AccessIntel\Support\McaAccessIntelView;
use Mca\AccessLog\Models\AccessLog;

class IntelController extends Controller
{
    public function __construct(private readonly AccessIntelService $intel) {}

    public function index(Request $request): View
    {
        $level = (string) $request->query('level', 'all');
        $fresh = $request->boolean('fresh');

        if ($fresh) {
            $this->intel->forgetCache();
        }

        $ranked = $this->intel->rankedIps();
        if (in_array($level, ['healthy', 'watch', 'risky'], true)) {
            $ranked = $ranked->where('level', $level)->values();
        }

        return McaAccessIntelView::render('intel.index', [
            'ranked' => $ranked,
            'counts' => $this->intel->overviewCounts(),
            'filterLevel' => $level,
            'windowHours' => (int) config('access-intel.window_hours', 24),
            'accessLogAvailable' => $this->intel->accessLogAvailable(),
            'firewallAvailable' => $this->intel->firewallAvailable(),
        ]);
    }

    public function ip(string $ip): View
    {
        $summary = $this->intel->summarizeIp($ip);
        $recent = collect();

        if ($this->intel->accessLogAvailable()) {
            $recent = AccessLog::query()
                ->forIp($ip)
                ->where('created_at', '>=', $this->intel->windowStart())
                ->latest('id')
                ->limit(40)
                ->get();
        }

        return McaAccessIntelView::render('intel.ip', [
            'summary' => $summary,
            'recent' => $recent,
            'firewallAvailable' => $this->intel->firewallAvailable(),
            'windowHours' => (int) config('access-intel.window_hours', 24),
        ]);
    }

    public function block(Request $request, string $ip): RedirectResponse
    {
        if (! $this->intel->firewallAvailable()) {
            return back()->withErrors(['firewall' => mca_intel('errors.firewall_missing')]);
        }

        mca_firewall()->blacklist($ip, [
            'label' => mca_intel('actions.block_label'),
            'reason' => mca_intel('actions.block_reason'),
            'is_active' => true,
        ], $request->user()?->getAuthIdentifier());

        $this->intel->forgetCache();

        return redirect()
            ->route(config('access-intel.routes.web.name_prefix', 'mca.access-intel.').'ip', ['ip' => $ip])
            ->with('mca_intel_status', mca_intel('flash.blocked', ['ip' => $ip]));
    }

    public function whitelist(Request $request, string $ip): RedirectResponse
    {
        if (! $this->intel->firewallAvailable()) {
            return back()->withErrors(['firewall' => mca_intel('errors.firewall_missing')]);
        }

        mca_firewall()->whitelist($ip, [
            'label' => mca_intel('actions.whitelist_label'),
            'reason' => mca_intel('actions.whitelist_reason'),
            'is_active' => true,
        ], $request->user()?->getAuthIdentifier());

        $this->intel->forgetCache();

        return redirect()
            ->route(config('access-intel.routes.web.name_prefix', 'mca.access-intel.').'ip', ['ip' => $ip])
            ->with('mca_intel_status', mca_intel('flash.whitelisted', ['ip' => $ip]));
    }
}
