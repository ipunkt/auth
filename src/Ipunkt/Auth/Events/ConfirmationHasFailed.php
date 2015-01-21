<?php namespace Ipunkt\Auth\Events;

use Exception;

class ConfirmationHasFailed {
	/**
	 * @var Exception
	 */
	public $exception;

	/**
	 * @param Exception $e
	 */
	public function __construct(Exception $e) {
		$this->exception = $e;
	}
}