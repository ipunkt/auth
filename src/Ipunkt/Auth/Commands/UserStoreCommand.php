<?php namespace Ipunkt\Auth\Commands;

use Ipunkt\Auth\models\UserInterface;

class UserStoreCommand {
	/**
	 * @var UserInterface
	 */
	public $user;

	/**
	 * @param UserInterface $user
	 */
	public function __construct(UserInterface $user) {
		$this->user = $user;
	}
}