<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 15.05.14
 * Time: 10:09
 */

namespace Ipunkt\Auth;


use Auth;
use Config;
use Hash;
use Illuminate\Support\MessageBag;
use Input;
use Redirect;
use View;

/**
 * Class LoginController
 * @package Ipunkt\Simpleauth
 *
 * Brings a simple login interface to be used with the package if nothing fancy is necessary
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

        if ( Auth::attempt($credentials, true) ) {
            $response = Redirect::intended('/')->with('success');
        } else {
            $errors = new MessageBag();
            $errors->add('message',  trans('auth::form.login failed', ['username' => $credentials[$identifier_field]]) );
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
        $identifier_field = Config::get('auth::user_table.login_through_field');
        $username = Auth::user()->$identifier_field;
        Auth::logout();
        return Redirect::route('auth.login')->with('success', trans('auth::user.logout success', ['user' => $username]) );
    }
} 
