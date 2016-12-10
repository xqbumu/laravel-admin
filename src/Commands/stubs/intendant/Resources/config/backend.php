<?php

return [

    /*
     * Laravel-intendant name.
     */
    'name'  => 'Laravel-{$stub_intendant_zone_upper}',

    /*
     * Laravel-intendant url prefix.
     */
    'prefix'    => '{$stub_intendant_zone}',

    /*
     * Laravel-intendant install directory.
     */
    'directory' => app_path('Intendant/{$stub_intendant_zone_upper}'),

    /*
     * Laravel-intendant title.
     */
    'title'  => 'Intendant',

    /*
     * Laravel-intendant auth setting.
     */
    'auth' => [
        'driver'   => 'session',
        'provider' => '',
        'model'    => Intendant\{$stub_intendant_zone_upper}\Auth\Database\Intendantistrator::class,
    ],

    /*
     * Laravel-intendant upload setting.
     */
    'upload'  => [

        'disk' => 'intendant',

        'directory'  => [
            'image'  => 'image',
            'file'   => 'file',
        ],

        'host' => 'http://localhost:8000/upload/',
    ],

    /*
     * Laravel-intendant dataintendant setting.
     */
    'dataintendant' => [
        'users_table' => 'intendant_users',
        'users_model' => InCore\Intendant\Auth\Database\Intendantistrator::class,

        'roles_table' => 'intendant_roles',
        'roles_model' => InCore\Intendant\Auth\Database\Role::class,

        'permissions_table' => 'intendant_permissions',
        'permissions_model' => InCore\Intendant\Auth\Database\Permission::class,

        'menu_table'  => 'intendant_menu',
        'menu_model'  => InCore\Intendant\Auth\Database\Menu::class,

        'operation_log_table'    => 'intendant_operation_log',
        'user_permissions_table' => 'intendant_user_permissions',
        'role_users_table'       => 'intendant_role_users',
        'role_permissions_table' => 'intendant_role_permissions',
        'role_menu_table'        => 'intendant_role_menu',
    ],

    /*
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-green                              |
    |---------------------------------------------------------|
     */
    'skin'    => 'skin-blue',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout'  => ['sidebar-mini'],

    'version'   => '1.0',
];
