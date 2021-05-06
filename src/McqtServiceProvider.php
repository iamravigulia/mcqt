<?php

namespace edgewizz\mcqt;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class McqtServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Edgewizz\Mcqt\Controllers\McqtController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // dd($this);
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__ . '/components', 'mcqt');
        Blade::component('mcqt::mcqt.open', 'mcqt.open');
        Blade::component('mcqt::mcqt.edit', 'mcqt.edit');
        // Blade::component('mcqt::mcqt.index', 'mcqt.index');
    }
}
