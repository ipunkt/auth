<?php  namespace Ipunkt\Auth\Commands;

use Illuminate\Config\Repository;
use Ipunkt\Auth\Events\UserWasCreated;
use Ipunkt\Auth\Events\UserWasRegistered;
use Ipunkt\Auth\Exceptions\UserNotStoredException;
use Ipunkt\Auth\models\UserInterface;
use Ipunkt\Auth\Repositories\RepositoryInterface;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;

class UserRegisterCommandHandler implements CommandHandler {
	use DispatchableTrait;
	use EventGenerator;

	/**
	 * @var array
	 */
	protected $extraFields;

	/**
	 * @var string
	 */
	protected $identifierFieldName;

	/**
	 * @var RepositoryInterface
	 */
	private $userRepository;

	/**
	 * @param RepositoryInterface $userRepository
	 */
	public function __construct(Repository $config, RepositoryInterface $userRepository) {
		$this->extraFields = $config->get('auth::user_table.extra_fields');
		$this->identifierFieldName = $config->get('auth::user_table.login_through_field');

		$this->userRepository = $userRepository;
	}

	/**
	 * Handle the command
	 *
	 * @param UserRegisterCommand $command
	 * @return UserInterface
	 * 
	 * @throws UserNotStoredException
	 */
	public function handle($command) {
		
		$newUser = $this->userRepository->create();
		
		$newUser->setIdentifier( $command->getFieldValue($this->identifierFieldName) );
		$newUser->setEmail( strtolower($command->email) );
		$newUser->setPassword( $command->password );
		
        foreach($this->extraFields as $extraField) {
            $fieldName = $extraField['name'];
            $newUser->setExtra($fieldName, $command->getExtraFieldValue($fieldName));
        }
		
		$this->raise(new UserWasCreated($newUser));
		
		return $newUser;
	}


}