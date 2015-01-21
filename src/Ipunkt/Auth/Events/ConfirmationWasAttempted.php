<?php namespace Ipunkt\Auth\Events;

class ConfirmationWasAttempted {
	/**
	 * @var array
	 */
	public $registrationData;

	/**
	 * @param array $registrationData
	 */
	public function __construct($registrationData) {
		$this->registrationData = $registrationData;
	}
}