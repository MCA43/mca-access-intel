@php
    $np = config('access-intel.routes.web.name_prefix', 'mca.access-intel.');
@endphp
<header class="mca-ui-shell" id="mcaUiShell">
    <div class="mca-ui-shell__wrap">
        <div class="mca-ui-shell__inner">
            <a href="{{ route($np.'index') }}" class="mca-ui-brand">
                <span class="mca-ui-brand__mark" aria-hidden="true">
                    @include('mca-access-intel::partials.icon', ['name' => 'activity'])
                </span>
                <span>{{ $mcaIntelTitle ?? mca_intel('app.brand') }}</span>
            </a>
            <button type="button" class="mca-ui-menu-btn" id="mcaUiMenuBtn" aria-expanded="false" aria-controls="mcaUiNav" aria-label="{{ mca_intel('app.nav_aria') }}">
                @include('mca-access-intel::partials.icon', ['name' => 'menu'])
            </button>
        </div>
        <nav class="mca-ui-nav" id="mcaUiNav" aria-label="{{ mca_intel('app.nav_aria') }}">
            @if(Route::has('mca.hub.index'))
                <a href="{{ route('mca.hub.index') }}" class="mca-ui-nav__link">{{ mca_intel('nav.back_mca') }}</a>
            @endif
            <a href="{{ route($np.'index') }}" class="mca-ui-nav__link {{ request()->routeIs($np.'*') ? 'mca-ui-nav__link--active' : '' }}">{{ mca_intel('nav.intel') }}</a>
            @if(Route::has('mca.access-log.index'))
                <a href="{{ route('mca.access-log.index') }}" class="mca-ui-nav__link">{{ mca_intel('nav.access_log') }}</a>
            @endif
            @if(Route::has('mca.firewall.index'))
                <a href="{{ route('mca.firewall.index') }}" class="mca-ui-nav__link">{{ mca_intel('nav.firewall') }}</a>
            @endif
        </nav>
    </div>
</header>
