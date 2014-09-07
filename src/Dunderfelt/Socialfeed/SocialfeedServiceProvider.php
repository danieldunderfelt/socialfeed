<?php namespace Dunderfelt\Socialfeed;

use Illuminate\Support\ServiceProvider;

class SocialfeedServiceProvider extends ServiceProvider {

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
		$this->package('dunderfelt/socialfeed');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('Socialfeed', function()
        {
            return $this->app->make('Dunderfelt\Socialfeed\Socialfeed');
        });

        $this->app->bind('Dunderfelt\Socialfeed\Interfaces\ContentRepository', function()
        {
            return $this->app->make('Dunderfelt\Socialfeed\Repositories\EloquentContentRepository');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
