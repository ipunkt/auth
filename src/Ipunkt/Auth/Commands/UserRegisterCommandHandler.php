<?php  namespace Ipunkt\Auth\Commands;

use Illuminate\Config\Repository;
use Ipunkt\Auth\Exceptions\UserNotStoredException;
use Ipunkt\Auth\models\UserInterface;
use Ipunkt\Auth\Repositories\RepositoryInterface;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

class UserRegisterCommandHandler implements CommandHandler {
	use DispatchableTrait;

	/**
	 * @var array
	 */
	protected $extraFields;
	
	/**
	 * @var RepositoryInterface
	 */
	private $userRepository;

	/**
	 * @param RepositoryInterface $userRepository
	 */
	public function __construct(Repository $config, RepositoryInterface $userRepository) {
		$this->extraFields = $config->get('auth::user_table.extra_fields');

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
		
		$newUser->setIdentifier( $command->identifier );
		$newUser->setEmail( strtolower($command->email) );
		$newUser->setPassword( $command->password );
		
        foreach($this->extraFields as $extraField) {
            $fieldName = $extraField['name'];
            $newUser->setExtra($fieldName, $command->getExtraFieldValue($fieldName));
        }
		
		if(! $this->userRepository->save($newUser) )
			throw new UserNotStoredException($newUser);
		
		return $newUser;
	}


}