<?php

namespace Intendant\{$stub_intendant_zone_upper}\Routing;

use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;
use Illuminate\Routing\Router as LaravelRouter;

class Router
{
    /**
     * Laravel Router.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Admin routes group attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * All admin routes.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Create a new admin router instance.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(LaravelRouter $router)
    {
        $this->router = $router;

        $this->prepareAttributes();
        $this->setModuleRoutes();
    }

    /**
     * Prepare module route group attributes.
     *
     * @return void
     */
    protected function prepareAttributes()
    {
        $this->attributes = [
            'prefix'        => config('intendant.'.Incore::getModuleZone().'.prefix'),
            'intendant'     => config('intendant.'.Incore::getModuleZone().'.intendant'),
            'namespace'     => Incore::controllerNamespace(),
            'middleware'    => ['web', Incore::getModuleZone()],
        ];
    }

    /**
     * Set auth route.
     *
     * @return void
     */
    protected function setModuleRoutes()
    {
        $attributes = $this->attributes;
        $attributes['prefix'] .= '/auth';

        $this->router->group($attributes, function ($router) {
            $attributes = ['middleware' => Incore::getModuleZone().'.permission:allow,administrator'];

            $router->group($attributes, function ($router) {
                $router->resources([
                    'users'       => '\Intendant\{$stub_intendant_zone_upper}\Controllers\Incore\UserController',
                    'roles'       => '\Intendant\{$stub_intendant_zone_upper}\Controllers\Incore\RoleController',
                    'permissions' => '\Intendant\{$stub_intendant_zone_upper}\Controllers\Incore\PermissionController',
                    'menu'        => '\Intendant\{$stub_intendant_zone_upper}\Controllers\Incore\MenuController',
                    'logs'        => '\Intendant\{$stub_intendant_zone_upper}\Controllers\Incore\LogController',
                ]);
            });

            $router->get('login', 'Auth\LoginController@showLoginForm');
            $router->post('login', 'Auth\LoginController@login');
            $router->get('logout', 'Auth\LoginController@logout');
            $router->post('logout', 'Auth\LoginController@logout');
        });
    }

    /**
     * Register admin routes.
     *
     * @return void
     */
    public function register()
    {
        $this->router->group($this->attributes, function ($router) {
            foreach ($this->routes as $method => $arguments) {
                foreach ($arguments as $argument) {
                    call_user_func_array([$router, $method], $argument);
                }
            }
        });
    }

    /**
     * Dynamically add routes to admin router.
     *
     * @param string $method
     * @param array  $arguments
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->router, $method)) {
            $this->routes[$method][] = $arguments;
        }
    }
}
