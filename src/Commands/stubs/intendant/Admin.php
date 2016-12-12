<?php

namespace Intendant\{$stub_intendant_zone_upper};

use Encore\Admin\Admin as EnAdmin;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

/**
 * Class Admin.
 */
class Admin extends EnAdmin
{
    /**
     * The module zone of the module to find the module config.
     *
     * @return string
     */
    const MODULE_ZONE = '{$stub_intendant_zone}';

    /**
     * Get namespace of controllers.
     *
     * @return string
     */
    public function controllerNamespace()
    {
        $directory = config('intendant.'.self::MODULE_ZONE.'.directory');

        return 'Intendant\\'.ucfirst(basename($directory)).'\\Controllers';
    }

    /**
     * Admin url.
     *
     * @param $url
     *
     * @return string
     */
    public static function url($url)
    {
        $prefix = (string) config('intendant.'.self::MODULE_ZONE.'.prefix');

        if (empty($prefix) || $prefix == '/') {
            return '/'.trim($url, '/');
        }

        return "/$prefix/".trim($url, '/');
    }

    /**
     * Left sider-bar menu.
     *
     * @return array
     */
    public function menu()
    {
        return call_user_func(array(self::configs()['database']['menu_model'], 'toTree'));
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return Auth::guard(config('intendant.'.self::MODULE_ZONE.'.intendant.zone'))->user();
    }

    /**
     * @return mixed
     */
    public function configs()
    {
        return config('intendant.'.self::MODULE_ZONE);
    }

    /**
     * @return string
     */
    public static function getModuleZone()
    {
        return config('intendant.'.self::MODULE_ZONE.'.intendant.zone');
    }

    /**
     * @return string
     */
    public static function getModuleZoneUpper()
    {
        return ucfirst(self::getModuleZone());
    }
}
