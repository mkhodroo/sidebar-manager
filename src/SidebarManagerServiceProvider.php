<?php

namespace SidebarManager;

use Illuminate\Support\ServiceProvider;
use SidebarManager\Services\SidebarImageService;

class SidebarManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sidebar-manager.php', 'sidebar-manager');

        $this->app->singleton(SidebarImageService::class, function () {
            return new SidebarImageService();
        });
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sidebar-manager');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sidebar-manager.php' => config_path('sidebar-manager.php'),
            ], 'sidebar-manager-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/sidebar-manager'),
            ], 'sidebar-manager-views');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'sidebar-manager-migrations');
        }
    }
}
