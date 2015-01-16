<?php namespace Ipunkt\Auth\Commands;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Config\Repository as Config;
use Ipunkt\Auth\Exceptions\ValidationFailedException;

class UserEditValidator {
	/**
	 * @var Validator
	 */
	private $validator;

	/**
	 * @var array
	 */
	private $rules = [
		'password' => 'confirmed',
	];

	/**
	 * @param Config $config
	 * @param Validator $validator
	 */
	public function __construct(Config $config, Validator $validator) {
		$extraFields = $config->get('auth::user_table.extra_fields');
		foreach($extraFields as $extraField) {
			$this->rules[$extraField['name']] = $extraField['validation_rules'];
		}
		
		$this->validator = $validator;
	}

	/**
	 * @param UserEditCommand $command
	 * @return bool
	 */
	public function validate(UserEditCommand $command) {
		$validator = $this->validator->make($command->toArray(), $this->rules);

		if( $validator->fails())
			throw new ValidationFailedException($validator->errors());
		else if($this->passwordChangeFailed($command)  )
			throw new ValidationFailedException( new MessageBag(['old_password' => trans('validation.provide_password')] ) );

		return true;
	}

	/**
	 * @param UserEditCommand $command
	 * @return bool
	 */
	protected function passwordChangeFailed(UserEditCommand $command) {
		return $command->passwordWasChanged() && !$command->passwordMatches();
	}
}