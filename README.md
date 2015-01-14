[![Latest Stable Version](https://poser.pugx.org/ipunkt/auth/v/stable.svg)](https://packagist.org/packages/ipunkt/auth) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/auth/v/unstable.svg)](https://packagist.org/packages/ipunkt/auth) [![License](https://poser.pugx.org/ipunkt/auth/license.svg)](https://packagist.org/packages/ipunkt/auth) [![Total Downloads](https://poser.pugx.org/ipunkt/auth/downloads.svg)](https://packagist.org/packages/ipunkt/auth)
ipunkt/auth
===========
This package provides a user model and simple login, remind and register views plus.  
It also integrates nicely with other ipunkt packages like ipunkt/roles and ipunkt/social-auth to provides a pull-in and
use environment.

# Install

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
	
- Edit the 'user_table' variable to suit your needs
- Migrate the user table by doing

        `php artisan migrate --package=ipunkt/auth`

## Use

Link your users to the `auth.login` route to login
Link your users to the `auth.logout` route to logout

### User index
If you wish to publish a list of all users you can use to the auth.user.login route.
There are 3 ways to decide access to this:
- If you set the `publish_user_index` to `true` in this packages config then everyone will be able to view it.
- Otherwise if a user is logged in, it will be asked for permission to `index` on itself `Auth::user()->can('index', Auth::user())`.
        If you use the `PermissionChecker` which is included in this package then adding `index` to the `user_actions` array will grant access to all users
        If you wish for a more complicated behaviour, simply bring your own `PermissionChecker` for your user model. See https://github.com/ipunkt/permissions for detail
- If you neither set `publish_user_index` nor allow `index` in your user models `PermissionChecker` then it will fail through `App::abort(403)`

The default behaviour is to deny access.

### User edit
To let your users edit a profile link them to the `auth.user.edit` route, with the user id / `$user->getKey()` as Parameter
- This will ask the User model for `edit` permission
        If you use the `PermissionChecker` which is included in this package then adding `edit` to the `user_actions` array will grant permission to edit the users _own_ profile
	If you wish for a more complicated behaviour, simply bring your own `PermissionChecker` for your user model. See https://github.com/ipunkt/permissions for detail

The default behaviour is to allow editing permissions to the users own profile.

### User delete
To let your users delete an account, link them to the `auth.user.delete` route, with the user id / `$user->getKey()` as Parameter.
Also if a user has permission to delete an account while viewing the `auth.user.edit` view of said account, a deletion link will be shown.

This will ask the user model for `delete` permission
        If you use the `PermissionChecker` which is included in this package then adding `delete` to the `user_actions` array will grant permission to edit the users _own_ profile
	If you wish for a more complicated behaviour, simply bring your own `PermissionChecker` for your user model. See https://github.com/ipunkt/permissions for detail

The default behaviour is to to deny deletion permission

## Customization

### Views

- To use the packaged views but bring display them within your own template set the view.extends variables.
    - view is the layout view
    - section is the name of the section in which you want it displayed
- To change the views entirely publish them by doing  
    `php artisan view:publish ipunkt/auth`  
    then edit them to your likes

### Change out user model
To use your own model set the config value 'auth.model' to its classpath.
If your model does not inherit from eloquent you will also have to replace
'Ipunkt\Auth\Repositories\RepositoryInterface' in the Laravel IoC

If you simply want to extend the model brought by this package have your own model inherit from Eloquent(/Ardent/...)
and implement Ipunkt\Auth\User\UserInterface.

All prebuild functionality is capsulated in traits and can be used directly in your new model

1. EloquentUserTrait combines
    * EloquentUserInterfaceTrait implements the laravel UserInterface for you(pre-4.2)
    * EloquentUserRemindableTrait implements the laravel RemindableInterface for you(pre-4.2)
