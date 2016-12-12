<?php

// 只能修改该处
$intendant_zone = '{$stub_intendant_zone}';

return [

    /*
     * Laravel-intendant infomation important.
     */
    'intendant' => [
        'zone' => $intendant_zone,
    ],

    /*
     * Laravel-intendant name.
     */
    'name'  => 'Laravel-'.ucfirst($intendant_zone),

    /*
     * Laravel-intendant url prefix.
     */
    'prefix'    => $intendant_zone,

    /*
     * Laravel-intendant install directory.
     */
    'directory' => base_path('Core/Intendant/'.$intendant_zone),

    /*
     * Laravel-intendant title.
     */
    'title'  => ucfirst($intendant_zone),

    /*
     * Laravel-intendant auth setting.
     */
    'auth' => [
        'driver'   => 'session',
        'provider' => 'eloquent',
        'model'    => Intendant\{$stub_intendant_zone_upper}\Auth\Database\Administrator::class,
    ],

    /*
     * Laravel-intendant upload setting.
     */
    'upload'  => [

        'disk' => 'admin',

        'directory'  => [
            'image'  => 'image',
            'file'   => 'file',
        ],

        'host' => 'http://localhost:8210/upload',
    ],

    /*
     * Laravel-intendant database setting.
     */
    'database' => [
        'connection' => $intendant_zone,

        'users_table' => 'users',
        'users_model' => Intendant\{$stub_intendant_zone_upper}\Auth\Database\Administrator::class,
        'users_seed'  => Intendant\{$stub_intendant_zone_upper}\Auth\Database\AdminTablesSeeder::class,

        'roles_table' => 'roles',
        'roles_model' => Intendant\{$stub_intendant_zone_upper}\Auth\Database\Role::class,

        'permissions_table' => 'permissions',
        'permissions_model' => Intendant\{$stub_intendant_zone_upper}\Auth\Database\Permission::class,

        'menu_table'  => 'menu',
        'menu_model'  => Intendant\{$stub_intendant_zone_upper}\Auth\Database\Menu::class,

        'operation_log_table'    => 'operation_log',
        'operation_log_model'  => Intendant\{$stub_intendant_zone_upper}\Auth\Database\OperationLog::class,

        'user_permissions_table' => 'user_permissions',

        'role_users_table'       => 'role_users',

        'role_permissions_table' => 'role_permissions',

        'role_menu_table'        => 'role_menu',

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
