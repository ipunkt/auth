<?php namespace Ipunkt\Auth;

use Ipunkt\Auth\Events\ConfirmationHasFailed;
use Laracasts\Commander\Events\DispatchableTrait;
use Laracasts\Commander\Events\EventGenerator;
use Request;
use Auth;
use Config;
use Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\MessageBag;
use Input;
use Ipunkt\Auth\Exceptions\UserNotDeletedException;
use Ipunkt\Auth\Exceptions\UserNotStoredException;
use Ipunkt\Auth\Exceptions\ValidationFailedException;
use Ipunkt\Auth\models\UserInterface;
use Ipunkt\Auth\Repositories\RepositoryInterface;
use Ipunkt\SocialAuth\SocialAuth;
use Laracasts\Commander\CommanderTrait;
use Redirect;
use Session;
use Symfony\Component\Process\Exception\RuntimeException;
use Validator;
use View;

/**
 * Class UserController
 *
 * @package Ipunkt\Auth
 */
class UserController extends \Controller {

    use DispatchableTrait;
    use EventGenerator;
    use CommanderTrait;

    /**
     * @var Repositories\RepositoryInterface
     */
    protected $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository) {
        $this->repository = $repository;
    }

    protected function getUser($userId) {
        if($userId instanceof UserInterface)
            return $userId;

        return $this->repository->findOrFail($userId);
    }

    /**
     * Show a list of all users
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        $users = $this->repository->all();

        $response = null;
        $user = Auth::user();

        if( Config::get('auth::publish_user_index')
                || ($user !== null && $user->can('index', $user))
        ) {

			$variables = [];
			$variables['users'] = $users;
			$variables['extends'] = Config::get('auth::view.extends');

            $response = View::make('auth::user.index', $variables);

        } else {
            $response = App::abort(403);
        }



        return $response;
    }

    /**
     * Display register new user form.
     *
     * @return \Illuminate\View\View
     */
    public function create() {

        $variables = [];
        $variables['extends'] = Config::get('auth::view.extends');
        $variables['extra_fields'] = Config::get('auth::user_table.extra_fields');
	    if(class_exists('Ipunkt\SocialAuth\SocialAuth')) {
			if(\Ipunkt\SocialAuth\SocialAuth::hasRegistration())
				$variables['registerInfo'] = \Ipunkt\SocialAuth\SocialAuth::getRegistration();
	    }

        // Renew registerInfo in the session for the store call.
        Session::reflash();

        $view = View::make('auth::register', $variables);

        return $view;

    }

    /**
     * Attempt to save the new user supplied by Input to the database
     *
     * @return \Illuminate\Http\RedirectResponse - Back with errors if user not valid
     *                                          - to auth::login if new user is valid
     */
    public function store() {
        $response = null;

        try {
			$newUser = $this->execute( 'Ipunkt\Auth\Commands\UserRegisterCommand' );

            $response = Redirect::to(route('auth.login'));
        } catch(ValidationFailedException $e) {
            $response = Redirect::back()->withInput()->withErrors($e->getErrors());
        } catch(UserNotStoredException $e) {
            $response = Redirect::back()->withErrors(['email' => $e->getMessage()]);
        }

        return $response;
    }

    /**
     * Display the edit form for the user.
     *
     * @param $userId
     * @return \Illuminate\View\View
     * @internal param UserInterface $user
     */
    public function edit($userId) {
        $user = $this->getUser($userId);

        $response = null;
        $extends = Config::get('auth::view.extends');

        //$permission = 'user.'.$user->getId().'.edit';
        if(Auth::user()->can("edit", $user)) {
            $variables = [];
            $variables['extends'] = $extends;
            $variables['user'] = $user;
            $variables['extra_fields'] = Config::get('auth::user_table.extra_fields');
            $variables['can_enable'] = (Auth::user()->can('disable', $user) && !Auth::user()->isEqual($user));

            $response = View::make('auth::user/edit', $variables);
        } else {
            $errors = new MessageBag();
            $errors->add('message', trans('auth::user.permission_failed',
                    ['action' => trans('auth::user.edit permission'), 'object'=> trans('auth::user.user')]));
            $response = Redirect::route('home')->withErrors($errors);
        }

        return $response;
    }

    /**
     * Attempt to save the data from the edit form to the user in the database.
     *
     * @param UserInterface $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($userId) {
        $user = $this->getUser($userId);

        // Make sure we have permission to update this user
        if(Auth::user()->can('edit', $user)) {

            try {
				$changes = $this->execute( 'Ipunkt\Auth\Commands\UserEditCommand', array_merge(['user' => $user], Input::all() ) );

                $response = Redirect::route('auth.user.edit', $userId)->with($changes)->with(['message' => 'success']);
            } catch(ValidationFailedException $e) {
                $response = Redirect::back()->withInput()->withErrors($e->getErrors());
			} catch(UserNotStoredException $e) {
                $response = Redirect::back()->withInput()->withErrors(['message' => $e->getMessage()]);
            }

            return $response;
        } else {
            $response = Redirect::route('home')->with('message', trans('auth::user.permission_failed',
                ['action' => trans('auth::user.edit permission'), 'object'=> trans('auth::user.user')]));
        }
        return $response;
    }

    /**
     * Delete User
     *
     * @param UserInterface $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($userId) {
        $user = $this->getUser($userId);

        $response = null;
        if(Auth::user()->can('delete', $user)) {

            $username = $user->getIdentifier();

            try {
                $this->execute( 'Ipunkt\Auth\Commands\UserDeleteCommand', array_merge(['user' => $user], Input::all() ) );
                $response = Redirect::route('auth.login')->with('success', trans('auth::user.delete success', ['username' => $username]));

            } catch(UserNotDeletedException $e) {
                $response = Redirect::back()->withErrors(['message' => $e->getMessage()]);
            }

        } else {
            $response = Redirect::back()->withErrors([
                'error' => trans('auth::user.delete permission denied', ['username' => $user->getIdentifier()])
            ]);
        }
        return $response;
    }

    /**
     * @param string $key
     */
    public function confirm($key = null) {
        $extends = Config::get('auth::view.extends');

        try {

            $response = $this->execute( 'Ipunkt\Auth\Commands\UserConfirmCommand', ['data' => Request::all()] );

        } catch(RuntimeException $e) {
            $this->raise(new ConfirmationHasFailed($e));

            $this->dispatchEventsFor($this);

            $response = View::make('auth::user.confirmation.failure', compact('extends'));
        }


        return $response;
    }
}
