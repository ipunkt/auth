<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 15.05.14
 * Time: 09:14
 */
return [
    /**
     * set config value auth.Model to \Ipunkt\Auth\EloquentModel?
     *
     * Leave this at true to use the User model provided in this package for your laravel authentication
     * 
     * Set this to false if you bring your own User model.
     */
    'set_usermodel' => true,
    
	/**
	 * Leave this at true if you wish to use the repository provided in this package.
	 * The default repository builds models based on the auth.model config value
	 * 
	 * Set this to false if you bring your own Repository, please make sure it binds to and implements
	 * Ipunkt\Auth\Repositories\RepositoryInterface
	 * 
	 * shall we bind our repository to Ipunkt\Auth\Repositories\RepositoryInterface or do you bring your own?
	 */
    'set_repository' => true,

	/**
	 * This is prepended to all paths in this packages routes.php
	 * the prefix may not end with a slash
	 * Also note that setting this will mean /login becomes $route_prefix.'/login', thus the default 'auth' filter won't
	 * redirect to it anymore
	 * 
	 * example: 'route_prefix' => 'auth',
	 */
	'route_prefix' => '',

    /**
     * Leave this at true to use the packages internal email reminder view.
     * 
     * Set to false and set the config value auth.reminder.email to your own view to send custom reminder emails
     * 
     * set config value auth.reminder.email to auth::reminder/email?
     */
    'set_reminder' => true,

    'user_table' => [
        'table_name' => 'users',

        // This is the field name for authenticating lookup.
        // setting this to a field that does not exist in the database will crash the package.
        'login_through_field' => 'email',

        /* Extra fields for simpleauth to manage
         * ATTENTION: removing a field which is not nullable from the extra fields AFTER migrating will break registering
         *
         * Required fields:
         * name: Database/Form/Access name
         * form type: The name of the Formbuilder function which will be called to allow changing this field
         * validation_rules: Rules as passed to the validator
         *
         * Migration fields
         * These are optional if you specify 'do not migrate' => true
         * if you do you'll have to add the field to the table yourself
         * database type: Schemabuilder function used to create this field
         *
         * Optional fields:
         * not null: Database field will not be nullable if this is set
         * unique: Database field will be unique if this is set
         * not during register: Field will not apear during registration
         *
         */
        'extra_fields' => [
                /*['name' => 'testfield', 'database_type' => 'string', 'form_type' => 'text', 'validation_rules' => 'min:5',
                    'not during register' => true],
                ['name' => 'username', 'database_type' => 'string', 'form_type' => 'text', 'validation_rules' => 'required|min:5',
                    'not_null' => true, 'unique' => true]*/
            ]
    ],

    'view' => [
        // Define which view we should extend(your layout view)
        'extends' => [
            //'view' => 'auth::nomaster',
            // Set this if you do not want to bring your own layout

            'view' => 'auth::nomaster',
            'section' => 'content'
        ]
    ]
];
