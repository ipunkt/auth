<?php namespace Ipunkt\Auth\Exceptions;

use Ipunkt\Auth\models\UserInterface;
use RuntimeException;

class UserNotStoredException extends RuntimeException {
	/**
	 * @var UserInterface
	 */
	private $user;

	/**
	 * @param UserInterface $user
	 */
	public function __construct(UserInterface $user) {

		$this->user = $user;
		parent::__construct('Failed to store user');
	}

	/**
	 * @return UserInterface
	 */
	public function getUser() {
		return $this->user;
	}

}