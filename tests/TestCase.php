<?php

namespace Yakuzan\Boiler\Tests;

use Illuminate\Database\Schema\Blueprint;
use Laracasts\TestDummy\Factory;
use Yakuzan\Boiler\BoilerServiceProvider;
use Yakuzan\Boiler\Tests\Stubs\Entities\Permission;
use Yakuzan\Boiler\Tests\Stubs\Entities\Role;
use Yakuzan\Boiler\Tests\Stubs\Entities\User;
use Yakuzan\Boiler\Tests\Stubs\Providers\AuthServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [AuthServiceProvider::class, BoilerServiceProvider::class];
    }

    public function tearDown()
    {
        \Schema::drop('lessons');
        \Schema::drop('permission_role');
        \Schema::drop('permissions');
        \Schema::drop('role_user');
        \Schema::drop('roles');
        \Schema::drop('users');
        \Mockery::close();
    }

    /** @var User $younes */
    protected $younes;

    /** @var User $imane */
    protected $imane;

    /** @var Role $admin */
    protected $admin;

    /** @var Role $user */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->younes = Factory::create(User::class, ['name' => 'younes', 'email' => 'younes.khoubza@gmail.com']);

        $this->imane = Factory::create(User::class, ['name' => 'imane', 'email' => 'bahiaoui.imane@gmail.com']);

        $this->admin = Factory::create(Role::class, ['name' => 'admin']);

        $this->user = Factory::create(Role::class, ['name' => 'user']);

        $this->younes->attachRole($this->admin);

        $this->imane->attachRole($this->user);

        $lesson_view = Factory::create(Permission::class, ['name' => 'view_lesson']);
        $lesson_create = Factory::create(Permission::class, ['name' => 'create_lesson']);
        $lesson_update = Factory::create(Permission::class, ['name' => 'update_lesson']);
        $lesson_delete = Factory::create(Permission::class, ['name' => 'delete_lesson']);

        $this->admin->attachPermissions([$lesson_view, $lesson_create, $lesson_update, $lesson_delete]);
        $this->user->attachPermissions([$lesson_view]);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('entrust', [
            'role'                  => Role::class,
            'roles_table'           => 'roles',
            'user'                  => User::class,
            'users_table'           => 'users',
            'permission'            => Permission::class,
            'permissions_table'     => 'permissions',
            'permission_role_table' => 'permission_role',
            'role_user_table'       => 'role_user',
        ]);

        $app['config']->set('boiler', [
            'entities_namespace'     => 'Yakuzan\\Boiler\\Tests\\Stubs\\Entities',
            'controllers_namespace'  => 'Yakuzan\\Boiler\\Tests\\Stubs\\Controllers',
            'transformers_namespace' => 'Yakuzan\\Boiler\\Tests\\Stubs\\Transformers',
            'services_namespace'     => 'Yakuzan\\Boiler\\Tests\\Stubs\\Services',
            'policies_namespace'     => 'Yakuzan\\Boiler\\Tests\\Stubs\\Policies',
        ]);

        \Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subject');
            $table->timestamps();
        });

        \Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        \Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        \Schema::create('role_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        \Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        \Schema::create('permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        \Schema::create('guess', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        \Schema::create('defaults', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }
}
