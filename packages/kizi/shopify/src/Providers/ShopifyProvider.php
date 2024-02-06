<?php

namespace Kizi\Shopify\Providers;

use Illuminate\Support\ServiceProvider;
use Kizi\Shopify\Services\ShopifyService;
use Kizi\Shopify\Http\Middleware\ShopifyAuthenticate;

class ShopifyProvider extends ServiceProvider
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
        $this->configProvider();
        $this->middlewareProvider($router);
        $this->routeProvider();
    }

    private function registerProvider()
    {
        $this->app->singleton('shopify', function() {
            return new ShopifyService();
        });
    }

    private function configProvider()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/shopify.php', 'shopify'
        );
    }

    private function middlewareProvider($router)
    {
        $router->aliasMiddleware('auth.shopify', ShopifyAuthenticate::class);
    }

    private function routeProvider()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
