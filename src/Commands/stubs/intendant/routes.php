<?php

use Illuminate\Routing\Router;
use Intendant\{$stub_intendant_zone_upper}\Facades\Incore;

// Web
Route::group([
    'prefix'        => config('intendant.{$stub_intendant_zone}.prefix'),
    'intendant'     => config('intendant.{$stub_intendant_zone}.intendant'),
    'namespace'     => Incore::controllerNamespace(),
    'middleware'    => ['web', '{$stub_intendant_zone}'],
], function (Router $router) {

    $router->get('/', 'HomeController@index');
});

// API
Route::group([
    'prefix'        => config('intendant.{$stub_intendant_zone}.prefix').'/api',
    'namespace'     => Incore::controllerNamespace(),
    'middleware'    => ['api'],
], function (Router $router) {
});
