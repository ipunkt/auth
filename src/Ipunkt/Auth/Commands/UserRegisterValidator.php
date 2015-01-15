<?php namespace Ipunkt\Auth\Commands;

use Illuminate\Config\Repository;
use Ipunkt\Auth\Exceptions\ValidationFailedException;
use Validator;

class UserRegisterValidator {
	
	private $rules = [
		'email' => 'required|email',
		'password' => 'required|min:1',
	];
	/**
	 * @var Validator
	 */
	private $validator;

	/**
	 * @param Repository $config
	 * @param Validator $validator
	 */
	public function __construct(Repository $config, Validator $validator) {
	    $extraFields = $config->get('auth::user_table.extra_fields');
		foreach($extraFields as $extraField) {
			$this->rules[$extraField['name']] = $extraField['validation_rules'];
		}
		$this->validator = $validator;
	}

	/**
	 * @param UserRegisterCommand $command
	 * @return bool
	 */
	public function validate(UserRegisterCommand $command) {
		/**
		 * @var Validator $validator
		 */
		$validator = $this->validator->make($command->toArray(), $this->rules);
		
		if( $validator->fails() )
			throw new ValidationFailedException($validator->errors());
		
		return true;
	}
}