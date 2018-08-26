<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class WgAPIServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Wargaming\API::class, function ($app) {
            return new Wargaming\API('268c4563cd5f273c94aca7b3faf2cc57', Wargaming\LANGUAGE_ENGLISH, 'api.worldofwarships.eu'); //WOWS TOOLS TEST
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Wargaming\API::class];
    }
}
