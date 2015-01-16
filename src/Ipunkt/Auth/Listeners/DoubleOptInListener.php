<?php namespace Ipunkt\Auth\Listeners;

use Ipunkt\Auth\Events\UserWasCreated;
use Ipunkt\Auth\Events\UserWasRegistered;
use Laracasts\Commander\CommanderTrait;
use Laracasts\Commander\Events\EventListener;

/**
 * Class DoubleOptInListener
 * @package Ipunkt\Auth\Listeners
 */
class DoubleOptInListener extends EventListener {
	
	use CommanderTrait;

	/**
	 * @param UserWasCreated $event
	 */
	public function whenUserWasCreated(UserWasCreated $event) {
		$user = $event->user;
		$user->setEnabled(false);

		$this->execute( 'Ipunkt\Auth\Commands\UserStoreCommand', compact('user') );
	}
	
	public function whenUserWasRegistered(UserWasRegistered $event) {
		$this->execute( 'Ipunkt\Auth\Commands\UserActivationEmailCommand', compact('user') );
	}
	
}