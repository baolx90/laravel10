<?php

namespace Kizi\AppSubscriptions\Providers;

use Illuminate\Support\ServiceProvider;

class AppSubscriptionsProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProvider();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        $this->viewProvider();
        $this->configProvider();
        $this->middlewareProvider($router);
        $this->routeProvider();
    }

    private function viewProvider()
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'app_subscriptions');
    }

    private function registerProvider()
    {
    }

    private function configProvider()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/app_subscriptions.php', 'app_subscriptions'
        );
    }

    private function middlewareProvider($router)
    {

    }

    private function routeProvider()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
