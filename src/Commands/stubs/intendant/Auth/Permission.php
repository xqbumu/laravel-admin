<?php

namespace Intendant\{$stub_intendant_zone_upper}\Auth;

use Intendant\{$stub_intendant_zone_upper}\Facades\Admin;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Define the intendant_zone.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = Admin::getModuleZone();

        $this->setConnection($connection);
    }

    /**
     * Check permission.
     *
     * @param $permission
     *
     * @return true
     */
    public static function check($permission)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                call_user_func([Permission::class, 'check'], $permission);
            });

            return;
        }

        if (Auth::guard(Admin::getModuleZone())->user()->cannot($permission)) {
            static::error();
        }
    }

    /**
     * Roles allowed to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function allow($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (!Auth::guard(Admin::getModuleZone())->user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function deny($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (Auth::guard(Admin::getModuleZone())->user()->inRoles($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     */
    protected static function error()
    {
        $content = Admin::content(function ($content) {
            $content->body(view('admin::deny'));
        });

        response($content)->send();
        exit;
    }

    /**
     * If current user is administrator.
     *
     * @return mixed
     */
    public static function isAdministrator()
    {
        return Auth::guard(Admin::getModuleZone())->user()->isRole('administrator');
    }
}
