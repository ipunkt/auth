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
     * if set to true then the service provider will Config::set('auth.Model', 'Ipunkt\Auth\EloquentModel').
     *
     * default is true to allow the package workflow of
     *  install -> migrate -> works with sane defaults.
     */
    'set_usermodel' => true,
    'set_repository' => true,

    /**
     * set config value auth.reminder.email to auth::reminder/email?
     */
    'set_reminder' => true,

    'user_table' => [
        'table_name' => 'users',

        // This is the field which will be looked for when authenticating.
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