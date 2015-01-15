<?php namespace Ipunkt\Auth\Events;

use Ipunkt\Auth\models\UserInterface;

class UserWasRegistered {
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