<?php namespace MainstreamCT\WordPressAPI;

use Illuminate\Support\ServiceProvider;

class WordPressAPIServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('wp-api.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WpApi::class, function ($app) {

            $endpoint = $this->app['config']->get('wp-api.endpoint');
            $auth     = ['username' => $this->app['config']->get('wp-api.username'), 'password' => $this->app['config']->get('wp-api.password')];
            $client   = $this->app->make('GuzzleHttp\Client');

            return new WpApi($endpoint, $client, $auth);

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['wp-api'];
    }
}
