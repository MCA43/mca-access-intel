<?php

namespace Mca\AccessIntel\Support;

use Illuminate\Contracts\View\View;

final class McaAccessIntelView
{
    public static function layout(): string
    {
        return (string) config('access-intel.views.layout', 'mca-access-intel::layouts.app');
    }

    public static function render(string $view, array $data = []): View
    {
        McaAccessIntelLocale::apply();

        $namespace = config('access-intel.views.namespace', 'mca-access-intel');

        return view($namespace.'::'.$view, array_merge([
            'mcaIntelTitle' => config('access-intel.ui.title') ?: mca_intel('app.title'),
        ], $data));
    }

    public static function uiCssUrl(): string
    {
        return asset((string) config('access-intel.ui.assets.ui', 'vendor/mca-permission/mca-ui.css'));
    }

    public static function uiJsUrl(): string
    {
        return asset((string) config('access-intel.ui.assets.ui_js', 'vendor/mca-permission/mca-ui.js'));
    }

    public static function cssUrl(): string
    {
        return asset((string) config('access-intel.ui.assets.css', 'vendor/mca-access-intel/mca-access-intel.css'));
    }

    public static function jsUrl(): string
    {
        return asset((string) config('access-intel.ui.assets.js', 'vendor/mca-access-intel/mca-access-intel.js'));
    }
}
