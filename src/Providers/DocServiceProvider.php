<?php

namespace Smart\ApiDoc\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Smart\ApiDoc\Console\InstallCommand;
use Smart\ApiDoc\Services\ConfigService;

class DocServiceProvider extends ServiceProvider
{
    protected $namespace = 'Smart\ApiDoc\Http\Controllers';

    /**
     * 启动
     */
    public function boot()
    {
        if (!ConfigService::enabled()) {
            return;
        }

        $this->registerRoutes();
        $this->registerPublishing();
        $this->registerViews();
    }

    /**
     * 注册服务
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configFile(), ConfigService::key());

        $this->registerCommands();
    }


    /**
     * 注册可以作为发布的包
     */
    protected function registerPublishing()
    {
        //if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../public' => public_path('vendor/smart'),
            ], 'doc-assets');

            $this->publishes([
                $this->configFile() => config_path('smart-doc.php'),
            ], 'doc-config');
        //}
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom($this->routeFile());
        });
    }

    /**
     * 注册命令
     */
    protected function registerCommands()
    {
        //if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        //}
    }

    /**
     * 注册试图文件
     */
    protected function registerViews()
    {
        //if (!$this->app->runningInConsole()) {
            $this->loadViewsFrom(
                __DIR__ . '/../../resources/views',
                'doc'
            );
        //}
    }

    /**
     * Get the doc route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'domain' => ConfigService::domain(),
            'namespace' => 'Smart\ApiDoc\Http\Controllers',
            'prefix' => ConfigService::prefix(),
            'middleware' => 'web',
        ];
    }

    /**
     * @return string
     */
    protected function configFile()
    {
        return __DIR__ . '/../../config/smart-doc.php';
    }

    /**
     * @return string
     */
    protected function routeFile()
    {
        return __DIR__ . '/../../routes/smart-doc.php';
    }

}
