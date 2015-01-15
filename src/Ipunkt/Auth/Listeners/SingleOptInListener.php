<?php namespace Ipunkt\Auth\Listeners;

use Ipunkt\Auth\Events\UserWasCreated;
use Laracasts\Commander\CommanderTrait;
use Laracasts\Commander\Events\EventListener;

class SingleOptInListener extends EventListener {
	
	use CommanderTrait;

	/**
	 * @param UserWasCreated $event
	 */
	public function whenUserWasCreated(UserWasCreated $event) {
		$user = $event->user;
		$this->execute( 'Ipunkt\Auth\Commands\UserStoreCommand', compact('user') );
	}
}