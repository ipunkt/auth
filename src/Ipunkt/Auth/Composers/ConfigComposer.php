<?php namespace Ipunkt\Auth\Composers;
use Illuminate\View\View;
use Illuminate\Config\Repository as Config;

/**
 * Class ConfigComposer
 * @package Ipunkt\Auth\Composers
 *
 * This class is responsible to pass the `auth::view.*` config values to all `auth::*` views - to be used in the master layout
 */
class ConfigComposer {
	/**
	 * @var array|null
	 */
	private $extends;

	/**
	 * @var array|null
	 */
	private $extraFields;

	/**
	 * @param Config $config
	 */
	public function __construct(Config $config) {
		$this->extends = $config->get('auth::view.extends');
		$this->extraFields = $config->get('auth::user_table.extra_fields');
	}

	/**
	 * @param View $view
	 */
	public function compose($view) {
		$view->with('extends', $this->extends)
			->with('extra_fields', $this->extraFields);
	}
}