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
	public function boot()
	{
		$this->package('ipunkt/auth');
        if(Config::get('auth::set usermodel') == true) {
            Config::set('auth.model', 'Ipunkt\Auth\models\EloquentUser');
        }
        if(Config::get('auth::set repository') == true)
            App::bind('Ipunkt\Auth\Repositories\RepositoryInterface', 'Ipunkt\Auth\Repositories\EloquentRepository');

        /*
         * FIXME: having this overwritten in permissions causes 'rebound' events to fire, which attempt to make a new
         *      PermissionChecker without setting an associated object.
         */
        /*if(Config::get('auth::set permission checker') == true)
            App::bind('Ipunkt\Auth\PermissionChecker\PermissionCheckerInterface',
                    'Ipunkt\Auth\PermissionChecker\DummyPermissionChecker'
            );*/
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        require_once __DIR__ . "/../../routes.php";
		//
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
