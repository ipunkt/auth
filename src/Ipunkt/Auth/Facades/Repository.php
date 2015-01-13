<?php namespace Ipunkt\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Repository
 *
 * @package Ipunkt\Auth\Facades
 */
class Repository extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Ipunkt\Auth\Repositories\RepositoryInterface';
	}
}