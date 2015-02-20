<?php namespace Ipunkt\Auth;

use App;
use Config;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory as View;
use Ipunkt\Auth\Listeners\SingleOptInListener;

/**
 * Class AuthServiceProvider
 *
 * Load routes and set auth.model to Ipunkt\Auth\EloquentUser if configured
 *
 * @package Ipunkt\Auth
 */
class AuthServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	/**
	 * @var Repository
	 */
	private $config;
	/**
	 * @var Dispatcher
	 */
	private $event;

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @param Application $app
	 * @param Repository $config
	 * @param Dispatcher $event
	 */
	public function __construct(Application $app) {
	    parent::__construct($app);

		$this->config = $this->app->make( 'Illuminate\Config\Repository' );
		$this->event = $this->app->make( 'Illuminate\Events\Dispatcher' );
		$this->view = $this->app->make( 'Illuminate\View\Factory' );
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ipunkt/auth');

		if ($this->config->get('auth::set_usermodel', false) == true)
			$this->config->set('auth.model', 'Ipunkt\Auth\models\EloquentUser');

		if ($this->config->get('auth::set_repository', false) == true)
			$this->app->bind('Ipunkt\Auth\Repositories\RepositoryInterface', 'Ipunkt\Auth\Repositories\EloquentRepository');

		$this->registerEventListeners();
		$this->registerViewComposers();

		require_once __DIR__ . "/../../routes.php";
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
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

	protected function registerEventListeners() {
		$registrationModel = $this->config->get('auth::registration_strategy');

		switch ($registrationModel) {
			case 'single_opt_in':
				$this->event->listen('Ipunkt.Auth.*', 'Ipunkt\Auth\Listeners\SingleOptInListener');
				break;
			case 'double_opt_in':
				$this->event->listen('Ipunkt.Auth.*', 'Ipunkt\Auth\Listeners\DoubleOptInListener');
				break;

			default:
		}
	}

	/**
	 * Registers the view composers for this package
	 */
	private function registerViewComposers() {
		$this->view->composer('auth::*', 'Ipunkt\Auth\Composers\ConfigComposer');
	}
}