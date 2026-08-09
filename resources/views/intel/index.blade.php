@php
    $np = config('access-intel.routes.web.name_prefix', 'mca.access-intel.');
@endphp
@extends(\Mca\AccessIntel\Support\McaAccessIntelView::layout())

@section('title', mca_intel('pages.index_title'))

@section('content')
    <div class="mca-intel-toolbar">
        <div>
            <h1 class="mca-perm-title">{{ mca_intel('pages.index_title') }}</h1>
            <p class="mca-perm-help">{{ mca_intel('hint.window', ['hours' => $windowHours]) }}</p>
        </div>
        <a href="{{ route($np.'index', ['level' => $filterLevel === 'all' ? null : $filterLevel, 'fresh' => 1]) }}" class="mca-ui-btn mca-ui-btn--secondary mca-ui-btn--sm">
            {{ mca_intel('actions.refresh') }}
        </a>
    </div>

    @unless ($accessLogAvailable)
        <div class="mca-perm-card">
            <div class="mca-perm-card__body">
                <p class="mca-perm-help">{{ mca_intel('hint.missing_log') }}</p>
            </div>
        </div>
    @else
        <div class="mca-intel-stats">
            <div class="mca-perm-card mca-intel-stat mca-intel-stat--healthy">
                <div class="mca-perm-card__body">
                    <div class="mca-intel-stat__label">{{ mca_intel('levels.healthy') }}</div>
                    <div class="mca-intel-stat__value">{{ $counts['healthy'] }}</div>
                </div>
            </div>
            <div class="mca-perm-card mca-intel-stat mca-intel-stat--watch">
                <div class="mca-perm-card__body">
                    <div class="mca-intel-stat__label">{{ mca_intel('levels.watch') }}</div>
                    <div class="mca-intel-stat__value">{{ $counts['watch'] }}</div>
                </div>
            </div>
            <div class="mca-perm-card mca-intel-stat mca-intel-stat--risky">
                <div class="mca-perm-card__body">
                    <div class="mca-intel-stat__label">{{ mca_intel('levels.risky') }}</div>
                    <div class="mca-intel-stat__value">{{ $counts['risky'] }}</div>
                </div>
            </div>
        </div>

        <div class="mca-intel-tabs">
            @foreach (['all', 'risky', 'watch', 'healthy'] as $tab)
                <a href="{{ route($np.'index', array_filter(['level' => $tab === 'all' ? null : $tab])) }}"
                   class="mca-intel-tabs__link {{ $filterLevel === $tab ? 'is-active' : '' }}">
                    {{ mca_intel('levels.'.$tab) }}
                </a>
            @endforeach
        </div>

        <div class="mca-perm-card">
            <div class="mca-perm-card__body mca-intel-table-wrap">
                @if ($ranked->isEmpty())
                    <p class="mca-perm-help">{{ mca_intel('table.empty') }}</p>
                @else
                    <table class="mca-intel-table">
                        <thead>
                            <tr>
                                <th>{{ mca_intel('fields.ip') }}</th>
                                <th>{{ mca_intel('fields.score') }}</th>
                                <th>{{ mca_intel('fields.level') }}</th>
                                <th>{{ mca_intel('fields.hits') }}</th>
                                <th>{{ mca_intel('fields.errors') }}</th>
                                <th>{{ mca_intel('fields.unique_paths') }}</th>
                                <th>{{ mca_intel('fields.last_seen') }}</th>
                                <th class="mca-intel-col-actions"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ranked as $row)
                                <tr>
                                    <td><a href="{{ route($np.'ip', ['ip' => $row['ip']]) }}"><code>{{ $row['ip'] }}</code></a></td>
                                    <td><strong class="mca-intel-score mca-intel-score--{{ $row['level'] }}">{{ $row['score'] }}</strong></td>
                                    <td><span class="mca-intel-badge mca-intel-badge--{{ $row['level'] }}">{{ mca_intel('levels.'.$row['level']) }}</span></td>
                                    <td>{{ number_format($row['hits']) }}</td>
                                    <td>{{ number_format($row['errors']) }}</td>
                                    <td>{{ number_format($row['unique_paths']) }}</td>
                                    <td>{{ $row['last_seen'] ? \Illuminate\Support\Carbon::parse($row['last_seen'])->diffForHumans() : '—' }}</td>
                                    <td class="mca-intel-col-actions">
                                        <div class="mca-intel-actions">
                                            <a href="{{ route($np.'ip', ['ip' => $row['ip']]) }}"
                                               class="mca-ui-btn mca-ui-btn--secondary mca-ui-btn--icon mca-intel-tip"
                                               data-tooltip="{{ mca_intel('actions.view') }}"
                                               title="{{ mca_intel('actions.view') }}"
                                               aria-label="{{ mca_intel('actions.view') }}">
                                                @include('mca-access-intel::partials.icon', ['name' => 'eye', 'class' => 'mca-ui-icon mca-ui-icon--xs'])
                                            </a>
                                            @if ($firewallAvailable)
                                                <form method="post" action="{{ route($np.'block', ['ip' => $row['ip']]) }}"
                                                      data-mca-confirm="{{ mca_intel('confirm.block') }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="mca-ui-btn mca-ui-btn--danger mca-ui-btn--icon mca-intel-tip"
                                                            data-tooltip="{{ mca_intel('actions.block') }}"
                                                            title="{{ mca_intel('actions.block') }}"
                                                            aria-label="{{ mca_intel('actions.block') }}">
                                                        @include('mca-access-intel::partials.icon', ['name' => 'ban', 'class' => 'mca-ui-icon mca-ui-icon--xs'])
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endunless

    <p class="mca-perm-help mca-intel-hint">{{ mca_intel('hint.suite') }}</p>
@endsection
