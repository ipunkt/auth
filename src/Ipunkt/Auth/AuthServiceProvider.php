<?php namespace Ipunkt\Auth;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package Ipunkt\Auth
 *
 * Load routes and set auth.model to Ipunkt\Auth\EloquentUser if configured
 */
class AuthServiceProvider extends ServiceProvider {

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
	public function boot() {
		$this->package('ipunkt/auth');
        if(Config::get('auth::set_usermodel') == true) {
            Config::set('auth.model', 'Ipunkt\Auth\models\EloquentUser');
        }
        if(Config::get('auth::set_repository') == true)
            App::bind('Ipunkt\Auth\Repositories\RepositoryInterface', 'Ipunkt\Auth\Repositories\EloquentRepository');
		require_once __DIR__ . "/../../routes.php";
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
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
