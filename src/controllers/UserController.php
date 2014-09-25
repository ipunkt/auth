<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 15.05.14
 * Time: 11:50
 */

namespace Ipunkt\Auth;


use Auth;
use Config;
use Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Input;
use Ipunkt\Auth\models\UserInterface;
use Ipunkt\Auth\Repositories\RepositoryInterface;
use Ipunkt\SocialAuth\SocialAuth;
use Redirect;
use Session;
use Validator;
use View;

/**
 * Class UserController
 * @package Ipunkt\Simpleauth
 *
 *
 */
class UserController extends \Controller {
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

    /**
     * Show a list of all users
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        $users = $this->repository->all();

        $variables = [];
        $variables['users'] = $users;
        $variables['extends'] = Config::get('auth::view.extends');


        return View::make('auth::user.index', $variables);
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
        $model = Config::get('auth.model');

        $identifier_field = Config::get('auth::user_table.login_through_field');
        $email_field = 'email';
        $password_field = 'password';
        $password_confirm_field = 'password_confirmation';

        $new_user = new $model(Input::all());
        /**
         * @var UserInterface $new_user
         */
        $new_user->setIdentifier(Input::get($identifier_field));
        $new_user->setEmail(strtolower( Input::get('email') ) );
        $new_user->setPassword( Input::get($password_field) );

        $rules = [
            $email_field => 'required|email',
        ];

        /**
         * This code works with the ipunkt/social-auth package.
         * if no social-auth user is attached
         * OR if a social-auth user is attached but does not allow logging in through it
         * -> require a password to be set
         */
	    if(!class_exists('Ipunkt\SocialAuth\SocialAuth')
		    || ! \Ipunkt\SocialAuth\SocialAuth::hasRegistration()
		    || ! $variables['registerInfo'] = \Ipunkt\SocialAuth\SocialAuth::getRegistration()->providesLogin() ) {
		    
		    $rules[$password_field] = 'required';
		    $rules[$password_confirm_field] = 'required|same:'.$password_field;
	    }

        foreach(Config::get('auth::user_table.extra_fields') as $extra_field) {
            $field_name = $extra_field['name'];
            $rules[$field_name] = $extra_field['validation_rules'];
            $new_user->setExtra($field_name, Input::get($field_name));
        }

        $validator = Validator::make(
            Input::all(),
            $rules
        );

        if($validator->passes()) {
            if( $new_user->save() ) {

                /**
                 * Triggers with ipunkt/social-auth
                 *
                 * If a registerInfo was attached to this login process notify it that the use was successfuly created
                 *  this will attach the social-auth user to the new user in the case of ipunkt/social-auth
                 */
                if(Session::has('registerInfo'))
                    Session::get('registerInfo')->success($new_user);

                $response = Redirect::to(route('auth.login'))->with('success', 'user.register success');
            } else
                $response = Redirect::back()->withErrors($new_user->validationErrors());
        } else {
            $response = Redirect::back()->withInput()->withErrors($validator);
        }


        return $response;
    }

    /**
     * Display the edit form for the user.
     *
     * @param UserInterface $user
     * @return \Illuminate\View\View
     */
    public function edit(UserInterface $user) {
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
    public function update(UserInterface $user) {
        // Make sure we have permission to update this user
        if(Auth::user()->can('edit', $user)) {

            // Grab all the Input
            $table_config = Config::get('auth::user_table');
            $email = Input::get('email');
            $extra_fields = $table_config['extra fields'];
            $password = Input::get('password');
            $old_password = Input::get('old_password');
            $password_confirm = Input::get('password_confirmation');
            $enabled = Input::get('enabled');
            $changes = [];

            $disable_user_permission_denied = false;

            // Test if the email changed
            if($email && $email != $user->getEmail()) {
                $user->setEmail($email);
                $changes['email'] = 'user.email_changed';
            }


            // Test if password was updated properly
            $password_was_changed = (strlen($password) > 0);
            $old_password_matches = ( (strlen($password) > 0) && (Hash::check($old_password, $user->getAuthPassword()) || Auth::user()->isSuperuser()) );
            $password_matches_password_confirm = ( (strlen($password) > 0) && ($password == $password_confirm) );
            $password_is_different_from_old_password = ( (strlen($password) > 0) && !Hash::check($password, $user->getAuthPassword()) );

            if( (strlen($password) > 0) && $password_is_different_from_old_password ) {
                $user->setPassword($password);
                $changes['password'] = 'user.password_changed';
            }

            $cant_disable_own_user = false;
            if(isset($enabled) && $enabled != $user->isEnabled()) {
                if (Auth::user()->can('disable', $user)) {
                    $changes['enabled'] = 'user.enabled_changed';
                    $user->setEnabled($enabled);
                    $cant_disable_own_user = Auth::user()->isEqual($user);
                } else {
                    $disable_user_permission_denied = true;
                }
            }

            foreach($extra_fields as $extra_field) {
                $field = $extra_field['name'];
                $value = Input::get($field);
                if($value && $value != $user->getExtra($field)) {
                    $user->setExtra($field, $value);
                    $changes['field'] = 'user.'.$field.'.changed';
                }
            }

            if( $user->validate()
                    && ( (!$password_was_changed) || ($old_password_matches && $password_matches_password_confirm && $password_is_different_from_old_password) )
                    && !$cant_disable_own_user && !$disable_user_permission_denied
            ) {
                $user->save();
                $response  = Redirect::route('auth.user.edit',[$user->getId()])->with('success', $changes);
                foreach($changes as $name => $value) {
                    $response->with($name, $value);
                }
            } else {
                $errors = $user->validationErrors();

                if($password_was_changed && !$old_password_matches)
                    $errors->add('old_password', 'user.password_missmatch');

                if($password_was_changed && !$password_matches_password_confirm )
                    $errors->add('password_confirmation', 'user.password_no_confirm');

                if ($password_was_changed && !$password_is_different_from_old_password)
                    $errors->add('password', 'user.password_old');

                if($cant_disable_own_user)
                    $errors->add('enabled', 'user.enabled_cant_disable_self');

                if($disable_user_permission_denied)
                    $errors->add('error', 'user.enable_permission_denied');

                $response = Redirect::back()->withErrors($errors)->withInput();
            }
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
    public function destroy(UserInterface $user) {
        $response = null;
        if(Auth::user()->can('delete', $user)) {
            $username = $user->getIdentifier();
            $this->repository->delete($user);
            $response = Redirect::route('auth.login')->with('success', trans('auth::user.delete success', ['username' => $username]));
        } else {
            $response = Redirect::back()->withErrors([
                'error' => trans('auth::user.delete permission denied', ['username' => $user->getIdentifier()])
            ]);
        }
        return $response;
    }
}