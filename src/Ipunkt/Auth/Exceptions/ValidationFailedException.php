<?php namespace Ipunkt\Auth\Exceptions;

use Illuminate\Support\MessageBag;
use RuntimeException;

/**
 * Class ValidationFailedException
 * @package Ipunkt\Auth\Exceptions
 */
class ValidationFailedException extends RuntimeException {
	/**
	 * @var MessageBag
	 */
	private $errors;

	/**
	 * @param MessageBag $errors
	 */
	public function __construct(MessageBag $errors) {
		$this->errors = $errors;
		
		parent::__construct('Validation failed');
	}

	/**
	 * @return MessageBag
	 */
	public function getErrors() {
		return $this->errors;
	}
}