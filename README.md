# ipunkt/auth

[![Latest Stable Version](https://poser.pugx.org/ipunkt/auth/v/stable.svg)](https://packagist.org/packages/ipunkt/auth) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/auth/v/unstable.svg)](https://packagist.org/packages/ipunkt/auth) [![License](https://poser.pugx.org/ipunkt/auth/license.svg)](https://packagist.org/packages/ipunkt/auth) [![Total Downloads](https://poser.pugx.org/ipunkt/auth/downloads.svg)](https://packagist.org/packages/ipunkt/auth)

This package provides a user model and simple login, plus remind and register views.
It integrates nicely with other ipunkt packages like [ipunkt/roles](https://github.com/ipunkt/roles) and [ipunkt/social-auth](https://github.com/ipunkt/social-auth) to provide a pull-in-and-use-it environment.

## Installation

Add to your composer.json following lines

	"require": {
		"ipunkt/auth": "1.*"
	}

## Configuration

- Add 

        'Ipunkt\Auth\AuthServiceProvider'
    
    to your service provider list.  
- Publish the config by doing

        `php artisan config:publish ipunkt/auth`
	
- Edit the `user_table` config file to suit your needs
  - `table_name` - The name the table will go by in the database
  - `login_through_field` - Which field is used to identify the user for login. Default is email. Username is also common
  - `extra_fields` - Array of additional fields which you want your user model to have. Each field should have the following keys
    - `name` - The field name in the Database
    - `database_type` - The field type in the database
    - `form_type` - The input type to use in the register and edit form
    - `validation_rules` - The laravel Validator rules to validate this field when registering or editing
    - `not during register` - if set to true then this field will not be available during registration. Only when editing
      the user
- Migrate the user table by doing

        `php artisan migrate --package=ipunkt/auth`
        
### Registration strategy

The registration strategy can be choosen in the `config.php` through the field `registration_strategy`

#### Single-opt-in
Currently only single-opt-in is provided by the package. This means the user will be logged in and activated once they
send valid user data to register.
Config Value: `single_opt_in`

#### Double-opt-in
In the future a native double-opt-in will be provided. This means the user will be created, but disabled after they send
valid user data. An email will be sent to their email address with a confirmation link. Their user account will be enabled
once the registration link has been visited
Config Value: `double_opt_in`

## Usage

Link your users to the route `auth.register` to allow them to register

Link your users to the route `auth.login` for logging a user in

Link your users to the route `auth.logout` for logging a user out

### User index
If you wish to publish a list of all users you can use to the auth.user.login route.
There are 3 ways to decide access to this:

- If you set the `publish_user_index` to `true` in this packages config then everyone will be able to view it.
- Otherwise if a user is logged in, it will be asked for permission to `index` on itself `Auth::user()->can('index', Auth::user())`.
  - If you use the `PermissionChecker` which is included in this package then adding `index` to the `user_actions` array will grant access to all users
  - If you wish for a more complicated behaviour, simply bring your own `PermissionChecker` for your user model. See [ipunkt/permissions](https://github.com/ipunkt/permissions) for detail
- If you neither set `publish_user_index` nor allow `index` in your user models `PermissionChecker` then it will fail through `App::abort(403)`

The default behaviour is to deny access.

### User edit
To let your users edit a profile link them to the `auth.user.edit` route, with the user id / `$user->getKey()` as Parameter

This will ask the User model for `edit` permission

  - If you use the `PermissionChecker` which is included in this package then adding `edit` to the `user_actions` array will grant permission to edit the users _own_ profile
  - If you wish for a more complicated behaviour, simply bring your own `PermissionChecker` for your user model. See [ipunkt/permissions](https://github.com/ipunkt/permissions) for detail

The default behaviour is to allow editing permissions to the users own profile.

### User delete
To let your users delete an account, link them to the `auth.user.delete` route, with the user id / `$user->getKey()` as Parameter.
Also if a user has permission to delete an account while viewing the `auth.user.edit` view of said account, a deletion link will be shown.

This will ask the user model for `delete` permission

- If you use the `PermissionChecker` which is included in this package then adding `delete` to the `user_actions` array will grant permission to edit the users _own_ profile
- If you wish for a more complicated behaviour, simply bring your own `PermissionChecker` for your user model. See [ipunkt/permissions](https://github.com/ipunkt/permissions) for detail

The default behaviour is to to deny deletion permission

## Customization

### Views

- To use the packaged views but display them within your own template set the view.extends variables.
    - view is the layout view
    - section is the name of the section in which you want it displayed
- To change the views entirely publish them by doing  
    `php artisan view:publish ipunkt/auth`  
    then edit them to your likes
    
### Misc Config Options

#### `set_usermodel`
Default value: `true`
When left at `true` then the AuthServiceProvider will set `auth.model` to the Model provided by this package
Set to `false` if you wish to use your own user model.

#### `set_repository`
Default value: `true`
When left at `true` then the AuthServiceProvider will bind this packages UserRepository to `Ipunkt\Auth\Repositories\RepositoryInterface`.
The default repository will instanciate the Model set in the config value `auth.model`.
Set to `false` if you do not use Eloquent or want to bind your own Repository for the User creation for some reason. see
`Change out user model` below

#### `route_prefix`
Default value: `''`
This prefix is prepended to all routes set by this package. This enables you to bundle your authentication users under a
single path, like `auth/`
If you set this, then make sure to check your `auth` filter. The default Laravel4 implementation sends the user to `/login`
upon failing

#### `set_reminder`
Default value: `true`
When left at `true` then the AuthServiceProvider will use `auth::reminder/email` as the View for reminder emails
Set to `false` if you wish to load a view from your app instead of customizing the package view

#### `publish_user_index`
Default value: `false`
When set to `true` then `$route_prefix/user/` will show a listing of all registered users if the `$user->can('index', $dummyUser)`
See `ipunkt/permissions` for specifics about giving permissions

#### `user_actions`
Default value: `['edit']`
This value configures the default PermissionChecker for the user model this package brings.
Any action listed in this config variable is see as on a user by user base and will be allowed if the user attempts to
do it to his own account.

#### `routes`
Default value: `['logout' => 'auth.login']`
This decides where varios actions redirect after they have successfuly finished.
Currently:
- `logout` User has logged out

### Extending Registration Strategies

To create your own registration strategy you will have to register an event listener to `Ipunkt.Auth.*`

Example:
{{{
class RegistrationServiceProvider extends ServiceProvider {
	public function boot() {
		if(Config::get('auth::registration_strategy') == 'test_opt_in')
		Event::listen('Ipunkt.Auth.*', 'Acme\Listeners\TestOptInListener');
	}
	
	public function register() {}
}}}

#### Events to listen for

- whenUserWasCreated(UserWasCreated $event)
  `UserWasCreated: UserInterface $user`
  This is called when a User has successfully sent valid registration data.
  It is this events responsibility to write the user to the database.
  The default way to do this is by calling the UserStoreCommand:
  `$this->execute( 'Ipunkt\Auth\Commands\UserStoreCommand', ['user' => $event->user] );`
- whenConfirmationWasAttempted(ConfirmationWasAttempted $confirmation)
  This is called when a user comes back from a registration link
  
- whenConfirmationWasSuccessful(ConfirmationWasSuccessful $confirmation)
  This is called when confirmation was successful

- whenConfirmationHasFailed(ConfirmationHasFailed $confirmation)
  This is called when confirmation has failed
  
Also see `Ipunkt\Auth\Listeners\SingleOptInListener` for reference

### Change out user model

To use your own model set the config value 'auth.model' to its classpath.

If your model does not inherit from eloquent you will also have to replace 'Ipunkt\Auth\Repositories\RepositoryInterface' in the Laravel IoC

If you simply want to extend the model brought by this package have your own model inherit from Eloquent and implement Ipunkt\Auth\User\UserInterface.

All prebuild functionality is capsulated in traits and can be used directly in your new model

1. EloquentUserTrait combines
    * EloquentUserInterfaceTrait implements the laravel UserInterface for you (pre-4.2)
    * EloquentUserRemindableTrait implements the laravel RemindableInterface for you (pre-4.2)
