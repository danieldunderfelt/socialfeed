<?php namespace Dunderfelt\Socialfeed;

use Illuminate\Support\ServiceProvider;
use Dunderfelt\Socialfeed\Repositories\EloquentContentRepository;

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

        \Route::get('update', function() {
            return \App::make('Dunderfelt\Socialfeed\NetworksManager')->update();
        });

        \App::resolving('Dunderfelt\Socialfeed\NetworksManager', function($networksManager) {
            $networksManager->loadNetworks();
        });
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

        $this->app->bind('Dunderfelt\Socialfeed\Repositories\ContentRepository', function()
        {
            return $this->app->make('Dunderfelt\Socialfeed\Repositories\EloquentContentRepository');
        });

        $this->app->bind('twitter', 'Dunderfelt\Socialfeed\Twitter');
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
