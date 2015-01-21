<?php namespace Ipunkt\Auth\Commands;

use Ipunkt\Auth\Events\ConfirmationWasSuccessful;
use Redirect;
use Ipunkt\Auth\Events\ConfirmationWasAttempted;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;

class UserConfirmCommandHandler implements CommandHandler {

	use DispatchableTrait;
	use EventGenerator;

	/**
	 * Handle the command
	 *
	 * @param UserConfirmCommand $command
	 * @return mixed
	 */
	public function handle($command) {

		$this->raise(new ConfirmationWasAttempted($command->data));

		$this->dispatchEventsFor($this);

		$this->raise(new ConfirmationWasSuccessful());

		$this->dispatchEventsFor($this);

		return Redirect::route('home');
	}


}