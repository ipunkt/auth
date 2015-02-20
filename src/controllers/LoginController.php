<?php namespace Ipunkt\Auth;

use Auth;
use Config;
use Hash;
use Illuminate\Support\MessageBag;
use Input;
use Redirect;
use View;

/**
 * Class LoginController
 *
 * Brings a simple login interface to be used with the package if nothing fancy is necessary
 *
 * @package Ipunkt\Simpleauth
 */
class LoginController extends \Controller {
    /**
     * Display the login form
     *
     * @return \Illuminate\View\View|null
     */
    public function loginForm() {
        $view = null;
        $extends = Config::get('auth::view.extends');

        $view = View::make('auth::login', ['extends' => $extends]);

        return $view;
    }

    /**
     * Attempt to log in witth the credentials sent through Input
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login() {
        $response = null;

        $identifier_field = Config::get('auth::user_table.login_through_field');
        $password_field = 'password';

        $credentials = [
            $identifier_field => Input::get($identifier_field),
            'password' => Input::get($password_field),
            'enabled' => true
        ];

        if(array_key_exists('email', $credentials))
            $credentials['email'] = strtolower($credentials['email']);

	    $rules = array();
	    $rules['password'] = 'required|min:1';
	    if(array_key_exists('email', $credentials))
		    $rules['email'] = 'required|email|exists:' . Config::get('auth::user_table.table_name');
	    if(array_key_exists('username', $credentials))
		    $rules['username'] = 'required|min:1|exists:' . Config::get('auth::user_table.table_name');

        $validator = \Validator::make($credentials, $rules);
    	if ($validator->fails()) {
		    return Redirect::back()->withInput()->withErrors($validator->errors());
	    }

        if ( Auth::attempt($credentials, true) ) {
            $response = Redirect::intended('/')->with('success');
        } else {
            $errors = new MessageBag();
            if (empty($credentials[$identifier_field]))
                $errors->add('message',  trans('auth::form.login failed') );
	        else
                $errors->add('message',  trans('auth::form.login failed_with_username', ['username' => $credentials[$identifier_field]]) );
            $response = Redirect::back()->withInput()->withErrors($errors);
        }

        return $response;
    }

    /**
     * Log out currently logged in User.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout() {

	    $logoutRedirectRoute = Config::get('auth::routes.redirect');

        $identifier_field = Config::get('auth::user_table.login_through_field');
        $username = Auth::user()->$identifier_field;
        Auth::logout();
        return Redirect::route('auth.login')->with('success', trans('auth::user.logout success', ['user' => $username]) );
    }
}
