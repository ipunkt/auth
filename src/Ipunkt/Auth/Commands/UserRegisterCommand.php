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
	 * @param string $email
	 * @param string $password
	 */
	public function __construct($email, $password) {

		$this->email = $email;
		$this->password = $password;
		$this->extraFields = \Input::except(['_token', 'email', 'password']);
		
	}
	
	public function getFieldValue($field) {
		if(in_array($field, ['email', 'password']))
			return $this->$field;
		
		return $this->extraFields[$field];
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
