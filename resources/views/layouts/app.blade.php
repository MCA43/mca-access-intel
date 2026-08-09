<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $mcaIntelTitle ?? mca_intel('app.title'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ \Mca\AccessIntel\Support\McaAccessIntelView::uiCssUrl() }}">
    <link rel="stylesheet" href="{{ \Mca\AccessIntel\Support\McaAccessIntelView::cssUrl() }}">
</head>
<body class="mca-ui-root mca-perm-root mca-intel-root">
    @include('mca-access-intel::partials.header')
    <main class="mca-ui-main mca-perm-main mca-intel-main">
        @include('mca-access-intel::partials.flash')
        @yield('content')
    </main>
    @php
        $mcaUiI18n = [
            'ok' => mca_intel('modal.ok'),
            'confirm' => mca_intel('modal.confirm'),
            'cancel' => mca_intel('modal.cancel'),
            'close' => mca_intel('modal.close'),
            'alert_title' => mca_intel('modal.alert_title'),
            'confirm_title' => mca_intel('modal.confirm_title'),
        ];
    @endphp
    <script>window.McaUiI18n = @json($mcaUiI18n);</script>
    <script src="{{ \Mca\AccessIntel\Support\McaAccessIntelView::uiJsUrl() }}" defer></script>
    <script src="{{ \Mca\AccessIntel\Support\McaAccessIntelView::jsUrl() }}" defer></script>
</body>
</html>
