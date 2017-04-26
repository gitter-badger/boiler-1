<?php

namespace Yakuzan\Boiler;

use Illuminate\Support\ServiceProvider;

class BoilerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/boiler.php', 'boiler');

        $this->registerMigrations();

        $this->publishes([
            __DIR__.'/../vendor/yajra/laravel-datatables-oracle/src/config/datatables.php'   => config_path('datatables.php'),
            __DIR__.'/../vendor/barryvdh/laravel-ide-helper/config/ide-helper.php'           => config_path('ide-helper.php'),
            __DIR__.'/../vendor/spatie/laravel-fractal/resources/config/laravel-fractal.php' => config_path('laravel-fractal.php'),
            __DIR__.'/config/entrust.php'                                                    => config_path('entrust.php'),
            __DIR__.'/config/boiler.php'                                                     => config_path('boiler.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
        $this->registerAliases();
        $this->registerMiddlewares();
    }

    private function registerServices()
    {
        if ('production' !== $this->app->environment()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        $this->app->register(\Spatie\Fractal\FractalServiceProvider::class);
        $this->app->register(\Zizaco\Entrust\EntrustServiceProvider::class);
    }

    private function registerAliases()
    {
        if (class_exists(\Illuminate\Foundation\AliasLoader::class)) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Form', \Collective\Html\FormFacade::class);
            $loader->alias('Html', \Collective\Html\HtmlFacade::class);
            $loader->alias('Fractal', \Spatie\Fractal\FractalFacade::class);
            $loader->alias('Entrust', \Zizaco\Entrust\EntrustFacade::class);
        }
    }

    private function registerMiddlewares()
    {
        $this->app['router']->middleware('role', \Zizaco\Entrust\Middleware\EntrustRole::class);
        $this->app['router']->middleware('permission', \Zizaco\Entrust\Middleware\EntrustPermission::class);
        $this->app['router']->middleware('ability', \Zizaco\Entrust\Middleware\EntrustAbility::class);
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->prependMiddleware(\Yakuzan\Boiler\Middlewares\ExceptionHandler::class);
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)->prependMiddleware(\Yakuzan\Boiler\Middlewares\BlacklistAction::class);
    }

    private function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}
