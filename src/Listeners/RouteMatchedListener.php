<?php

namespace Encore\Admin\Listeners;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;

class RouteMatchedListener
{
    /**
     * 处理事件
     *
     * @param  PodcastWasPurchased  $event
     * @return void
     */
    public function handle(RouteMatched $event)
    {
        $route_action = $event->route->getAction();

        if ($route_action && isset($route_action['intendant'])) {
            $intendant_zone = $route_action['intendant']['zone'];
            $loader = AliasLoader::getInstance();

            // 动态按模块加载 Admin，很重要
            $loader->alias('Admin', '\\Intendant\\'.ucfirst($intendant_zone).'\\Facades\\Admin');

            
            \Config::set('database.default', $intendant_zone);
        }
    }
}
