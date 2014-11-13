<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 15.05.14
 * Time: 10:08
 */

/**
 * Passwort reset filter
 *
 * Forces the user to set a new password if the account is flagged for it
 */
Route::filter('password_reset_flag', function($route) {
    if(Auth::check() && Auth::user()->getForcePasswordReset()
        // Exceptions to allow the user to reset the password
        && $route->getName() != 'auth.user.edit'
        && $route->getName() != 'auth.user.update'
        && $route->getName() != 'auth.logout'
    )
        return Redirect::route('auth.user.edit', Auth::user()->getId())->with('message', 'user.force_reset_set');
});
/**
 * Attach the password reset flag to all routes
 */
Route::when('*', 'password_reset_flag');

Route::when(Config::get('auth::route_prefix').'/user/*/edit', 'auth');

/**
 * Login routes
 */
Route::get(Config::get('auth::route_prefix').'/login', ['as' => 'auth.login', 'uses' => 'Ipunkt\Auth\LoginController@loginForm', 'before' => 'guest']);
Route::post(Config::get('auth::route_prefix').'/login', ['as' => 'auth.perform_login', 'uses' => 'Ipunkt\Auth\LoginController@login', 'before' => 'guest']);

/**
 * logout routes
 */
Route::get(Config::get('auth::route_prefix').'/logout', ['as' => 'auth.logout', 'uses' => 'Ipunkt\Auth\LoginController@logout', 'before' => 'auth']);

/**
 * Password reminder routes
 */
Route::get(Config::get('auth::route_prefix').'/remind', [
    'uses' => 'Ipunkt\Auth\ReminderController@request',
    'as' => 'auth.remind',
    'before' => 'guest']);
Route::post(Config::get('auth::route_prefix').'/remind', [
    'uses' => 'Ipunkt\Auth\ReminderController@perform',
    'as' => 'auth.perform_remind',
    'before' => 'guest']);
Route::get(Config::get('auth::route_prefix').'/reset/{token}', [
    'uses' => 'Ipunkt\Auth\ReminderController@reset',
    'as' => 'auth.reset_password',
    'before' => 'guest']);
Route::post(Config::get('auth::route_prefix').'/perform_reset', [
    'uses' => 'Ipunkt\Auth\ReminderController@performReset',
    'as' => 'auth.perform_reset_password',
    'before' => 'guest']);

Route::resource(Config::get('auth::route_prefix').'/user', 'Ipunkt\Auth\UserController', [
	'names' => [
		'index' => 'auth.user.index',
		'create' => 'auth.user.create',
		'store' => 'auth.user.store',
		'edit' => 'auth.user.edit',
		'update' => 'auth.user.update',
		'destroy' => 'auth.user.destroy',
	]
]);
