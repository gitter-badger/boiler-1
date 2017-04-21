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
        $this->publishes([
            __DIR__.'../vendor/yajra/laravel-datatables-oracle/src/config/datatables.php' => config_path('datatables.php'),
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
    }

    private function registerServices()
    {
        if ('production' !== $this->app->environment()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Yajra\Datatables\DatatablesServiceProvider::class);
        $this->app->register(\Spatie\Fractal\FractalServiceProvider::class);
    }

    private function registerAliases()
    {
        if (class_exists(\Illuminate\Foundation\AliasLoader::class)) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Form', \Collective\Html\FormFacade::class);
            $loader->alias('Html', \Collective\Html\HtmlFacade::class);
            $loader->alias('Fractal', \Spatie\Fractal\FractalFacade::class);
        }
    }
}
