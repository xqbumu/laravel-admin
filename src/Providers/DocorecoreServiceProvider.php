<?php

namespace Encore\Incore\Providers;

use Encore\Incore\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class IncoreServiceProvider extends ServiceProvider
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
        'incore.auth'        => \Encore\Incore\Middleware\Authenticate::class,
        'incore.pjax'        => \Encore\Incore\Middleware\PjaxMiddleware::class,
        'incore.log'         => \Encore\Incore\Middleware\OperationLog::class,
        'incore.permission'  => \Encore\Incore\Middleware\PermissionMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'incore' => [
            'incore.auth',
            'incore.pjax',
            'incore.log',
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

        if (file_exists($routes = incore_path('routes.php'))) {
            require $routes;

            $this->app['incore.router']->register();
        }
    }

    /**
     * Boot the service resources.
     *
     * @return void
     */
    public function bootResources()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'incore');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang/', 'incore');

        $this->publishes([__DIR__.'/../../resources/config/incore.php' => config_path('incore.php')], 'laravel-incore');
        $this->publishes([__DIR__.'/../../resources/assets' => public_path('packages/incore')], 'laravel-incore');
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
            'auth.guards.incore.driver'    => 'session',
            'auth.guards.incore.provider'  => 'incore',
            'auth.providers.incore.driver' => 'eloquent',
            'auth.providers.incore.model'  => 'Encore\Incore\Auth\Database\Administrator',
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
            'incore.router'  => \Encore\Incore\Routing\Router::class,
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
        $this->app->singleton('incore.router', function ($app) {
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
