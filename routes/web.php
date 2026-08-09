<?php

use Illuminate\Support\Facades\Route;

$web = config('access-intel.routes.web', []);
$prefix = $web['prefix'] ?? 'mca/access-intel';
$middleware = $web['middleware'] ?? ['web', 'auth', 'mca.access-intel.root', 'mca.access-intel.locale'];
$namePrefix = config('access-intel.routes.web.name_prefix', 'mca.access-intel.');
$controllers = config('access-intel.controllers.web', []);
$intel = $controllers['intel'] ?? \Mca\AccessIntel\Http\Controllers\Web\IntelController::class;

Route::prefix($prefix)
    ->middleware($middleware)
    ->name($namePrefix)
    ->group(function () use ($intel) {
        Route::get('/', [$intel, 'index'])->name('index');
        Route::get('/ip/{ip}', [$intel, 'ip'])->where('ip', '[0-9a-fA-F:\.]+')->name('ip');
        Route::post('/ip/{ip}/block', [$intel, 'block'])->where('ip', '[0-9a-fA-F:\.]+')->name('block');
        Route::post('/ip/{ip}/whitelist', [$intel, 'whitelist'])->where('ip', '[0-9a-fA-F:\.]+')->name('whitelist');
    });
