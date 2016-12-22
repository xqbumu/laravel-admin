<?php

namespace Intendant\{$stub_intendant_zone_upper}\Providers;

use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;
use Intendant\{$stub_intendant_zone_upper}\Routing\Router;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Inchow\Incore\Providers\IncoreServiceProvider;

class IntendantServiceProvider extends AdminServiceProvider
{

    /**
     * @var string
     */
    protected $moduleZone = '';

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [];

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->moduleZone = Incore::getModuleZone() ?: 'admin';

        $this->routeMiddleware = [
            $this->moduleZone.'.auth'        => '\\Intendant\\'.ucfirst($this->moduleZone).'\\Middleware\\Authenticate',
            $this->moduleZone.'.pjax'        => '\\Intendant\\'.ucfirst($this->moduleZone).'\\Middleware\\PjaxMiddleware',
            $this->moduleZone.'.log'         => '\\Intendant\\'.ucfirst($this->moduleZone).'\\Middleware\\OperationLog',
            $this->moduleZone.'.permission'  => '\\Intendant\\'.ucfirst($this->moduleZone).'\\Middleware\\PermissionMiddleware',
        ];

        $this->middlewareGroups = [
            $this->moduleZone => [
                $this->moduleZone.'.auth',
                $this->moduleZone.'.pjax',
                $this->moduleZone.'.log',
            ],
        ];

    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootResources();

        // 注册本地 views、i18n
        $this->loadViewsFrom(__DIR__.'/../Resources/views', $this->moduleZone);
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang/', $this->moduleZone);

        if (file_exists($routes = __DIR__.'/../routes.php')) {
            require $routes;

            $this->app[$this->moduleZone.'.router']->register();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            // TODO:: remove
            $loader = AliasLoader::getInstance();
            $loader->alias('Admin.'.$this->moduleZone, '\\Intendant\\'.ucfirst($this->moduleZone).'\\Providers\\Facades\\Incore');

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
            'auth.guards.'.$this->moduleZone.'.driver'    => config('intendant.'.$this->moduleZone.'.auth.driver'),
            'auth.guards.'.$this->moduleZone.'.provider'  => $this->moduleZone,
            'auth.providers.'.$this->moduleZone.'.driver' => config('intendant.'.$this->moduleZone.'.auth.provider'),
            'auth.providers.'.$this->moduleZone.'.model'  => config('intendant.'.$this->moduleZone.'.auth.model'),
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
            $this->moduleZone.'.router'  => '\\Intendant\\'.ucfirst($this->moduleZone).'\\Routing\\Router',
        ];

        foreach ($aliases as $key => $alias) {
            $this->app->alias($key, $alias);
        }
    }

    /**
     * Register module routes.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->app->singleton($this->moduleZone.'.router', function ($app) {
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
}
