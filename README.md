ipunkt/auth
===========
This package provides a user model and simple login, remind and register views plus.  
It also integrates nicely with other ipunkt packages like ipunkt/roles and ipunkt/social-auth to provides a pull-in and
use environment.

# Install

## Installation

Add to your composer.json following lines

	"require": {
		"ipunkt/auth": "dev-master"
	}

## Configuration

- Add 

        'Ipunkt\Auth\AuthServiceProvider'
    
    to your service provider list.  
- Publish the config by doing

        `php aristan config:publish ipunkt/auth`
	
- Edit the 'user_table' variable to suit your needs
- Migrate the user table by doing

        `php artisan migrate --package=ipunkt/auth`

## Use

Link your users to the auth.login route to login

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
