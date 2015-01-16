<?php namespace Ipunkt\Auth\Commands;

use Ipunkt\Auth\Events\UserWasRegistered;
use Ipunkt\Auth\Exceptions\UserNotStoredException;
use Ipunkt\Auth\Repositories\RepositoryInterface;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;

class UserStoreCommandHandler implements CommandHandler {
	use DispatchableTrait;
	use EventGenerator;
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
	 * @param UserStoreCommand $command
	 * @return mixed
	 */
	public function handle($command) {
		$newUser = $command->user;
		
		if(! $this->userRepository->save($newUser) )
			throw new UserNotStoredException($newUser);

		$this->raise(new UserWasRegistered($newUser));

		$this->dispatchEventsFor($this);
	}


}