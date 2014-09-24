# ipunkt/auth
Anoyed by writing the 27th user model and table for an laravel application?
This package attempts to take care of this tedious task once and for all.

## Simple Usage
Install, migrate, link to route 'simpleauth.login' to authenticate

## Advanced Usage

### Customize users table
Publish and edit the config.php and edit the 'user table' values
Do not change the value of this after you migrated, otherwise 'required' fields which have been removed will be impossible
to set in the create form, breaking the register procedure.

### Integrate with template
The package extends its own view 'simpleauth::nomaster' at the section 'content' by default. To integrate it into your
own template simply change the 'view' and 'section' values in the config file.

### Change views
To change the views publish them with 'php artisan view:publish ipunkt/simpleauth' and edit them to your likes.

### Change out user model
To use your own model set the config value 'auth.model' to its classpath.
If your model does not inherit from eloquent you will also have to replace
'Ipunkt\Simpleauth\Repositories\RepositoryInterface' in the Laravel IoC

If you simply want to extend the model brought by this package have your own model inherit from Eloquent(/Ardent/...)
and implement Ipunkt\simpleauth\models\UserInterface.

All prebuild functionality is capsulated in traits and can be used directly in your new model

1. EloquentUserTrait combines
    * EloquentUserInterfaceTrait implements the laravel UserInterface for you(pre-4.2)
    * EloquentUserRemindabelTrait implements the laravel RemindableInterface for you(pre-4.2)
2. DummyUserPermissionTrait implements a 'dumb' hasPermission function which allows a user to edit his/her own profile
3. HasPermissionTrait see ipunkt\simplepermissions
