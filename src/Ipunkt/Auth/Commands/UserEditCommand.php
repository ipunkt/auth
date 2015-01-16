<?php namespace Ipunkt\Auth\Commands;

use Ipunkt\Auth\models\UserInterface;

class UserEditCommand {
	public $email;
	public $old_password;
	public $password;
	public $password_confirmation;
	/**
	 * @var UserInterface
	 */
	public $user;
	/**
	 * @var array
	 */
	public $extraFields;

	/**
	 * @param UserInterface $user
	 * @param string $email
	 * @param string $old_password
	 * @param string $password
	 * @param string $password_confirmation
	 * @param array $extraFields
	 * @internal param string $username
	 * @internal param array $extraFields
	 * @internal param $password_confirm
	 */
	public function __construct(UserInterface $user, $email, $old_password, $password, $password_confirmation, $extraFields = []) {
		$this->email = $email;
		$this->old_password = $old_password;
		$this->password = $password;
		$this->password_confirmation = $password_confirmation;
		$this->user = $user;
		$this->extraFields = $extraFields;
	}
	
	public function toArray() {
		$array = [];
		
		foreach([
			        'email',
					'old_password',
			        'password',
			        'password_confirmation',
			        'user',
		        ] as $field) {
			$array[$field] = $this->$field;
		}
		
		$array += $this->extraFields;
		
		return $array;
	}
	
	public function emailWasChanged() {
		return ($this->email !== null && $this->email !== $this->user->getEmail());
	}
	
	public function passwordWasChanged() {
		return !empty( $this->password );
	}
	
	public function passwordMatches() {
		return \Hash::check($this->old_password, $this->user->getAuthPassword());
	}
	
	public function extraFieldWasChanged($fieldName) {
		return (array_key_exists($fieldName, $this->extraFields)
					&& $this->extraFields[$fieldName] !== $this->user->getExtra($fieldName) );
	}

}