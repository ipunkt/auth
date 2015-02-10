<?php namespace Ipunkt\Auth\Commands;

use Illuminate\Config\Repository;
use Ipunkt\Auth\Exceptions\ValidationFailedException;
use Illuminate\Validation\Factory as Validator;

class UserRegisterValidator
{
	/**
	 * validation ruleset
	 *
	 * @var array
	 */
	private $rules = [
		'email' => 'required|email',
		'password' => 'required|confirmed|min:1',
	];

	/**
	 * validator factory
	 *
	 * @var Validator
	 */
	private $validator;

	/**
	 * @param Repository $config
	 * @param Validator $validator
	 */
	public function __construct(Repository $config, Validator $validator)
	{
		$extraFields = $config->get('auth::user_table.extra_fields');
		foreach ($extraFields as $extraField) {
			$this->rules[$extraField['name']] = $extraField['validation_rules'];
		}
		$this->validator = $validator;

		$this->setupRules($config);
	}

	/**
	 * @param UserRegisterCommand $command
	 *
	 * @return bool
	 */
	public function validate(UserRegisterCommand $command)
	{
		/**
		 * @var Validator $validator
		 */
		$validator = $this->validator->make($command->toArray(), $this->rules);

		if ($validator->fails())
			throw new ValidationFailedException($validator->errors());

		return true;
	}

	/**
	 * setting up the rules with additional unique field validation
	 *
	 * @param \Illuminate\Config\Repository $config
	 */
	private function setupRules(Repository $config)
	{
		//  @TODO extra_fields in rules aufnehmen (Stichwort: unique)

		$tableName = $config->get('auth::user_table.table_name');

		$this->rules['email'] .= '|unique:' . $tableName . ',email';
	}
}
