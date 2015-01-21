<?php namespace Ipunkt\Auth\Listeners;

use Ipunkt\Auth\Events\ConfirmationHasFailed;
use Ipunkt\Auth\Events\ConfirmationWasAttempted;
use Ipunkt\Auth\Events\ConfirmationWasSuccessful;
use Ipunkt\Auth\Events\UserWasCreated;
use Laracasts\Commander\CommanderTrait;
use Laracasts\Commander\Events\EventListener;
use Symfony\Component\Process\Exception\RuntimeException;

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

	public function whenConfirmationWasAttempted(ConfirmationWasAttempted $attempted) {
	}

	public function whenConfirmationWasSuccessful(ConfirmationWasSuccessful $confirmation) {
	}

	public function whenConfirmationHasFailed(ConfirmationHasFailed $confirmation) {
	}
}