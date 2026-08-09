@php
    $np = config('access-intel.routes.web.name_prefix', 'mca.access-intel.');
    $s = $summary;
@endphp
@extends(\Mca\AccessIntel\Support\McaAccessIntelView::layout())

@section('title', mca_intel('pages.ip_title', ['ip' => $s['ip']]))

@section('content')
    <div class="mca-intel-toolbar">
        <div>
            <h1 class="mca-perm-title">{{ mca_intel('pages.ip_title', ['ip' => $s['ip']]) }}</h1>
            <p class="mca-perm-help">{{ mca_intel('hint.window', ['hours' => $windowHours]) }}</p>
        </div>
        <div class="mca-intel-toolbar__actions">
            <a href="{{ route($np.'index') }}" class="mca-ui-btn mca-ui-btn--ghost mca-ui-btn--sm">{{ mca_intel('nav.intel') }}</a>
            @if (Route::has('mca.access-log.ip'))
                <a href="{{ route('mca.access-log.ip', ['ip' => $s['ip']]) }}" class="mca-ui-btn mca-ui-btn--ghost mca-ui-btn--sm">{{ mca_intel('actions.open_log') }}</a>
            @endif
            @if ($firewallAvailable)
                <form method="post" action="{{ route($np.'block', ['ip' => $s['ip']]) }}" data-mca-confirm="{{ mca_intel('confirm.block') }}">
                    @csrf
                    <button type="submit" class="mca-ui-btn mca-ui-btn--danger mca-ui-btn--sm">{{ mca_intel('actions.block') }}</button>
                </form>
                <form method="post" action="{{ route($np.'whitelist', ['ip' => $s['ip']]) }}" data-mca-confirm="{{ mca_intel('confirm.whitelist') }}" data-mca-confirm-danger="0">
                    @csrf
                    <button type="submit" class="mca-ui-btn mca-ui-btn--primary mca-ui-btn--sm">{{ mca_intel('actions.whitelist') }}</button>
                </form>
            @endif
        </div>
    </div>

    <div class="mca-intel-stats">
        <div class="mca-perm-card">
            <div class="mca-perm-card__body">
                <div class="mca-intel-stat__label">{{ mca_intel('fields.score') }}</div>
                <div class="mca-intel-score mca-intel-score--{{ $s['level'] }} mca-intel-score--lg">{{ $s['score'] }}</div>
                <span class="mca-intel-badge mca-intel-badge--{{ $s['level'] }}">{{ mca_intel('levels.'.$s['level']) }}</span>
            </div>
        </div>
        <div class="mca-perm-card">
            <div class="mca-perm-card__body">
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.hits') }}</span><strong>{{ number_format($s['hits'] ?? 0) }}</strong></div>
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.errors') }}</span><strong>{{ number_format($s['errors'] ?? 0) }}</strong></div>
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.blocked') }}</span><strong>{{ number_format($s['blocked'] ?? 0) }}</strong></div>
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.unique_paths') }}</span><strong>{{ number_format($s['unique_paths'] ?? 0) }}</strong></div>
            </div>
        </div>
        <div class="mca-perm-card">
            <div class="mca-perm-card__body">
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.error_ratio') }}</span><strong>{{ number_format(($s['factors']['error_ratio'] ?? 0) * 100, 1) }}%</strong></div>
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.blocked_ratio') }}</span><strong>{{ number_format(($s['factors']['blocked_ratio'] ?? 0) * 100, 1) }}%</strong></div>
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.path_diversity') }}</span><strong>{{ number_format(($s['factors']['path_diversity'] ?? 0) * 100, 1) }}%</strong></div>
                <div class="mca-intel-stat-row"><span>{{ mca_intel('fields.last_seen') }}</span><strong>{{ $s['last_seen']?->diffForHumans() ?? '—' }}</strong></div>
            </div>
        </div>
    </div>

    <div class="mca-perm-card">
        <div class="mca-perm-card__header">{{ mca_intel('nav.access_log') }}</div>
        <div class="mca-perm-card__body mca-intel-table-wrap">
            @if ($recent->isEmpty())
                <p class="mca-perm-help">{{ mca_intel('table.empty') }}</p>
            @else
                <table class="mca-intel-table">
                    <thead>
                        <tr>
                            <th>{{ mca_intel('fields.last_seen') }}</th>
                            <th>Method</th>
                            <th>Path</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recent as $log)
                            <tr class="{{ $log->is_blocked ? 'is-blocked' : '' }}">
                                <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $log->method }}</td>
                                <td>{{ $log->path }}</td>
                                <td>{{ $log->status_code }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
