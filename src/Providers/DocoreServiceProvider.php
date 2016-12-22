<?php

namespace Encore\Incore\Providers;

use Encore\Incore\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class DocoreServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'CreateCommand',
        'MakeIntendantCommand',
        'MakeCommand',
        'MenuCommand',
        'InstallCommand',
        'UninstallCommand',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'docore.auth'        => \Encore\Incore\Middleware\Authenticate::class,
        'docore.pjax'        => \Encore\Incore\Middleware\PjaxMiddleware::class,
        'docore.log'         => \Encore\Incore\Middleware\OperationLog::class,
        'docore.permission'  => \Encore\Incore\Middleware\PermissionMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'docore' => [
            'docore.auth',
            'docore.pjax',
            'docore.log',
        ],
    ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootResources();

        if (file_exists($routes = Docore::incore_path('routes.php'))) {
            require $routes;

            $this->app['docore.router']->register();
        }
    }

    /**
     * Boot the service resources.
     *
     * @return void
     */
    public function bootResources()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'docore');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang/', 'docore');

        $this->publishes([__DIR__.'/../../resources/config/docore.php' => config_path('docore.php')], 'laravel-docore');
        $this->publishes([__DIR__.'/../../resources/assets' => public_path('packages/docore')], 'laravel-docore');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();

            $loader->alias('Incore', \Encore\Incore\Facades\Incore::class);

            $this->setupAuth();
        });

        $this->setupClassAliases();
        $this->registerRouteMiddleware();
        $this->registerCommands();

        $this->registerRouter();
    }

    /**
     * Setup auth configuration.
     *
     * @return void
     */
    protected function setupAuth()
    {
        config([
            'auth.guards.docore.driver'    => 'session',
            'auth.guards.docore.provider'  => 'docore',
            'auth.providers.docore.driver' => 'eloquent',
            'auth.providers.docore.model'  => 'Encore\Incore\Auth\Database\Administrator',
        ]);
    }

    /**
     * Setup the class aliases.
     *
     * @return void
     */
    protected function setupClassAliases()
    {
        $aliases = [
            'docore.router'  => \Encore\Incore\Routing\Router::class,
        ];

        foreach ($aliases as $key => $alias) {
            $this->app->alias($key, $alias);
        }
    }

    /**
     * Register incore routes.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->app->singleton('docore.router', function ($app) {
            return new Router($app['router']);
        });
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->middleware($key, $middleware);
        }

        // register middleware group.
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach ($this->commands as $command) {
            $this->commands('Encore\Incore\Commands\\'.$command);
        }
    }
}
