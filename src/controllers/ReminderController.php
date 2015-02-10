<?php namespace Ipunkt\Auth;

use App;
use Config;
use Controller;
use Illuminate\Support\MessageBag;
use Input;
use Lang;
use Password;
use Redirect;
use View;

/**
 * Class ReminderController
 *
 * Performs password reminder and reseting through the package
 * 
 * @package Ipunkt\Auth
 */
class ReminderController extends Controller {
    /**
     * Display the form to request a password reminder
     *
     * @return \Illuminate\View\View
     */
    public function request() {
        $variables = [];
        $variables['extends'] = Config::get('auth::view.extends');
        
        return View::make('auth::remind', $variables);
    }

    /**
     * Attempt to send a password reminder email to the email supplied by Input
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function perform() {
        $response = null;

        if (Config::get('auth::set_reminder'))
            Config::set('auth.reminder.email', 'auth::reminder/email');

        $result = Password::remind(Input::only('email'), function ($message) {
            $message->subject(trans('auth::reminders.email.subject'));
        });
        switch($result) {
            case Password::INVALID_USER:
                $response = Redirect::back()->with('error', Lang::get($result));
                break;

            case Password::REMINDER_SENT:
                $response = Redirect::back()->with('status', Lang::get($result));
                break;
        }

        return $response;
    }

    /**
     * Display the form to reset a user password after having received a token.
     *  This is usualy referenced by the password reminder email.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function reset($token = null) {
        if(is_null($token))
            App::abort(404);

        $variables = [];
        $variables['extends'] = Config::get('auth::view.extends');
        $variables['token'] = $token;
        
        return View::make('auth::reset', $variables);
    }

    /**
     * Update the password of the user if the supplied email is the same as the one we sent the token to.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function performReset() {
        $credentials['email'] = Input::get('email');
        $credentials['password'] = Input::get('password');
        $credentials['password_confirmation'] = Input::get('password_confirmation');
        $credentials['token'] = Input::get('token');
        /** @var $changed_user UserInterface */
        $changed_user = null;

        $result = Password::reset($credentials, function($user, $password) use (&$changed_user) {
            $changed_user = $user;
            $user->setPassword($password);

            $user->save();
        });

        switch ($result)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                $errors = new MessageBag();
                $errors->add('message', trans($result));
                return Redirect::back()->withErrors($errors)->withInput();

            case Password::PASSWORD_RESET:
                return Redirect::route('auth.login')->with('success', trans('auth::reminders.success'),
                        ['username' => $changed_user->getIdentifier()]);
        }
    }
}
