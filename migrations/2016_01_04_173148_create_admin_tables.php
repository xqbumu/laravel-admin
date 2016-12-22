<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = \Docore::configs('database.connection') ?: config('database.default');

        Schema::connection($connection)->create(\Docore::configs('database.users_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->unique();
            $table->string('password', 60);
            $table->string('name');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.roles_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.permissions_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50);
            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.menu_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri', 50);

            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.role_users_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.role_permissions_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.user_permissions_table'), function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.role_menu_table'), function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
        });

        Schema::connection($connection)->create(\Docore::configs('database.operation_log_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip', 15);
            $table->text('input');
            $table->index('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = \Docore::configs('database.connection') ?: config('database.default');

        Schema::connection($connection)->drop(\Docore::configs('database.users_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.roles_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.permissions_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.menu_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.user_permissions_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.role_users_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.role_permissions_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.role_menu_table'));
        Schema::connection($connection)->drop(\Docore::configs('database.operation_log_table'));
    }
}
