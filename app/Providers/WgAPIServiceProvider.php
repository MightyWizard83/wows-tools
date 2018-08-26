<?php

namespace App\Providers;

//use \Wargaming\API;
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
        //TODO: configurazione parametrica
        $this->app->singleton('Wargaming\API', function ($app) {
            return new \Wargaming\API('268c4563cd5f273c94aca7b3faf2cc57', \Wargaming\LANGUAGE_ENGLISH, 'api.worldofwarships.eu'); //WOWS TOOLS TEST
        });
        
        $this->app->singleton('RatingsExpected', function ($app) {
            return json_decode(file_get_contents("../storage/app/public/ratings-expected.json"), true);
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        //return [\Wargaming\API::class];
        return ['Wargaming\API', 'RatingsExpected'];
    }
}
