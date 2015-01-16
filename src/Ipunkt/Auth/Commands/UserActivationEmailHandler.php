<?php namespace Ipunkt\Auth\Commands;

use Laracasts\Commander\CommandHandler;

class UserActivationEmailHandler implements CommandHandler {

	/**
	 * @param UserActivationEmailCommand $command
	 */
	public function handle($command) {
		$user = $command->user;
		
		$email = $user->getEmail();
	}
}