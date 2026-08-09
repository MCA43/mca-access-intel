<?php

return [

    'enabled' => env('MCA_ACCESS_INTEL_ENABLED', true),

    'locale' => env('MCA_ACCESS_INTEL_LOCALE'),

    /*
    |--------------------------------------------------------------------------
    | Analysis window
    |--------------------------------------------------------------------------
    */
    'window_hours' => (int) env('MCA_ACCESS_INTEL_WINDOW_HOURS', 24),

    'cache' => [
        'enabled' => env('MCA_ACCESS_INTEL_CACHE', true),
        'ttl' => (int) env('MCA_ACCESS_INTEL_CACHE_TTL', 120),
        'key' => 'mca.access-intel.ranks',
    ],

    /*
    |--------------------------------------------------------------------------
    | Scoring thresholds (higher score = healthier)
    |--------------------------------------------------------------------------
    */
    'scoring' => [
        'healthy_min' => 70,
        'watch_min' => 40,
        // volume: hits in window
        'volume_soft' => 200,
        'volume_hard' => 1000,
        // error ratio = (4xx+5xx) / hits
        'error_soft' => 0.15,
        'error_hard' => 0.45,
        // unique paths / hits — scanning signal
        'path_diversity_soft' => 0.35,
        'path_diversity_hard' => 0.75,
        // blocked ratio
        'blocked_soft' => 0.05,
        'blocked_hard' => 0.25,
    ],

    'routes' => [
        'load_package_routes' => env('MCA_ACCESS_INTEL_LOAD_ROUTES', true),
        'web' => [
            'prefix' => env('MCA_ACCESS_INTEL_ROUTE_PREFIX', 'mca/access-intel'),
            'middleware' => array_filter(explode(',', (string) env(
                'MCA_ACCESS_INTEL_MIDDLEWARE',
                'web,auth,mca.access-intel.root,mca.access-intel.locale'
            ))),
            'name_prefix' => 'mca.access-intel.',
        ],
    ],

    'controllers' => [
        'web' => [
            'intel' => \Mca\AccessIntel\Http\Controllers\Web\IntelController::class,
        ],
    ],

    'views' => [
        'namespace' => env('MCA_ACCESS_INTEL_VIEW_NAMESPACE', 'mca-access-intel'),
        'layout' => env('MCA_ACCESS_INTEL_VIEW_LAYOUT', 'mca-access-intel::layouts.app'),
    ],

    'ui' => [
        'title' => env('MCA_ACCESS_INTEL_UI_TITLE'),
        'class_prefix' => 'mca-intel',
        'assets' => [
            'css' => 'vendor/mca-access-intel/mca-access-intel.css',
            'js' => 'vendor/mca-access-intel/mca-access-intel.js',
            'ui' => 'vendor/mca-permission/mca-ui.css',
            'ui_js' => 'vendor/mca-permission/mca-ui.js',
        ],
        'limit' => (int) env('MCA_ACCESS_INTEL_UI_LIMIT', 50),
    ],

    'access' => [
        'use_permission_root' => env('MCA_ACCESS_INTEL_USE_PERMISSION_ROOT', true),
        'role_column' => env('MCA_ACCESS_INTEL_ROLE_COLUMN', 'role_id'),
        'root_role' => env('MCA_ACCESS_INTEL_ROOT_ROLE', 'root'),
    ],

];
