<?php

namespace Mca\AccessIntel;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Mca\AccessIntel\Console\InstallAccessIntelCommand;
use Mca\AccessIntel\Http\Middleware\EnsureMcaAccessIntelRoot;
use Mca\AccessIntel\Http\Middleware\SetMcaAccessIntelLocale;
use Mca\AccessIntel\Services\AccessIntelService;

class AccessIntelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/access-intel.php', 'access-intel');
        $this->app->singleton(AccessIntelService::class);
    }

    public function boot(): void
    {
        if (! config('access-intel.enabled', true)) {
            return;
        }

        $this->registerPublishing();
        $this->registerMiddleware();
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'mca-access-intel');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'mca-access-intel');
        $this->registerRoutes();
        $this->registerHub();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallAccessIntelCommand::class,
            ]);
        }
    }

    protected function registerHub(): void
    {
        if (! function_exists('mca_hub_register')) {
            return;
        }

        mca_hub_register('access-intel', [
            'enabled' => fn () => (bool) config('access-intel.enabled', true),
        ]);
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/access-intel.php' => config_path('access-intel.php'),
        ], 'mca-access-intel-config');

        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mca-access-intel'),
        ], 'mca-access-intel-assets');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/mca-access-intel'),
        ], 'mca-access-intel-views');
    }

    protected function registerMiddleware(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('mca.access-intel.root', EnsureMcaAccessIntelRoot::class);
        $router->aliasMiddleware('mca.access-intel.locale', SetMcaAccessIntelLocale::class);
    }

    protected function registerRoutes(): void
    {
        if (! config('access-intel.routes.load_package_routes', true)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
