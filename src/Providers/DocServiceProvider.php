<?php

namespace Smart\ApiDoc\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Smart\ApiDoc\Console\InstallCommand;

class DocServiceProvider extends ServiceProvider
{
    protected $namespace = 'Smart\ApiDoc\Http\Controllers';

    /**
     * Bootstrap any package services.
     */
    public function boot()
    {
        if (!config('doc.enabled')) {
            return;
        }

        //Route::middlewareGroup('doc', config('doc.middleware', []));

        $this->registerRoutes();
        $this->registerPublishing();
        $this->registerViews();

        /*$this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'doc'
        );*/
    }

    public function register()
    {
        if (!$this->app->runningInConsole()) {
            $this->mergeConfigFrom(
                __DIR__ . '/../../config/doc.php',
                'doc'
            );
        }

        $this->registerCommands();

        /*$this->commands([
            InstallCommand::class,
        ]);*/
    }

    /**
     * 注册可以作为发布的包
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../public' => public_path('vendor/doc'),
            ], 'doc-assets');

            $this->publishes([
                __DIR__ . '/../../config/doc.php' => config_path('doc.php'),
            ], 'doc-config');
        }
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/doc.php');
        });
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    protected function registerViews()
    {
        if (!$this->app->runningInConsole()) {
            $this->loadViewsFrom(
                __DIR__ . '/../../resources/views',
                'doc'
            );
        }
    }

    /**
     * Get the doc route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'domain' => config('doc.domain', null),
            'namespace' => 'Smart\ApiDoc\Http\Controllers',
            'prefix' => config('doc.prefix'),
            'middleware' => 'web',
        ];
    }
}
