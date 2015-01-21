<?php namespace Ipunkt\Auth\Commands;

class UserConfirmCommand {
	/**
	 * @var array
	 */
	public $data;

	/**
	 * @param array $data
	 */
	public function __construct($data) {
		$this->data = $data;
	}
}