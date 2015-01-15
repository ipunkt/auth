<?php namespace Ipunkt\Auth\Commands;

class UserRegisterCommand {
	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $password;
	
	/**
	 * @var array
	 */
	public $extraFields;


	/**
	 * @var string
	 */
	public $identifier;

	/**
	 * @param $identifier
	 * @param $email
	 * @param $password
	 * @param array $extraFields
	 */
	public function __construct($identifier, $email, $password, $extraFields = []) {

		$this->email = $email;
		$this->password = $password;
		$this->extraFields = $extraFields;
		$this->identifier = $identifier;
		
	}

	/**
	 * Returns the value of an extra field
	 * 
	 * @param string $fieldName
	 * @return null
	 */
	public function getExtraFieldValue($fieldName) {
		if(!array_key_exists($fieldName, $this->extraFields))
			return null;
		
		return $this->extraFields[$fieldName];
	}

	public function toArray() {
		$fields = [
			'email' => $this->email,
			'password' => $this->password,
		];
		$fields += $this->extraFields;
		return $fields;
	}
}