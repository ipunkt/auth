<?php namespace Ipunkt\Auth\Listeners;

use Ipunkt\Auth\Events\UserWasCreated;
use Laracasts\Commander\CommanderTrait;
use Laracasts\Commander\Events\EventListener;

/**
 * Class SingleOptInListener
 * @package Ipunkt\Auth\Listeners
 */
class SingleOptInListener extends EventListener {
	
	use CommanderTrait;

	/**
	 * @param UserWasCreated $event
	 */
	public function whenUserWasCreated(UserWasCreated $event) {
		$user = $event->user;
		$user->setEnabled(true);
		
		$this->execute( 'Ipunkt\Auth\Commands\UserStoreCommand', compact('user') );
	}
}