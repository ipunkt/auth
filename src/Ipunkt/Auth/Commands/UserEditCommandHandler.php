<?php namespace Ipunkt\Auth\Commands;

use Ipunkt\Auth\Exceptions\UserNotStoredException;
use Ipunkt\Auth\Repositories\RepositoryInterface;
use Laracasts\Commander\CommandHandler;

class UserEditCommandHandler implements CommandHandler {
	/**
	 * @var RepositoryInterface
	 */
	private $userRepository;

	/**
	 * @param RepositoryInterface $userRepository
	 */
	public function __construct(RepositoryInterface $userRepository) {
		$this->userRepository = $userRepository;
	}
	
	/**
	 * Handle the command
	 *
	 * @param UserEditCommand $command
	 * @return mixed
	 */
	public function handle($command) {
		$user = $command->user;
		
		$changes = [];
		
		if( $command->emailWasChanged() ) {
			$user->setEmail($command->email);
			$changes['email'] = 'Changed';
		}
		
		if($command->passwordWasChanged() ) {
			$changes['password'] = 'Changed';
			$user->setPassword( $command->password );
		}
		
		foreach($command->extraFields as $field => $value) {
			if($command->extraFieldWasChanged($field))
				$user->setExtra($field, $value);
		}
		
		if(!$this->userRepository->save($user))
			throw new UserNotStoredException($user);
		
		return $changes;
	}


}