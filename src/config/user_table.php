<?php
/**
 * This config file is specific to the user migration and registration.
 * 
 * Migrating using the default values will create a user with 2 fields: email and password(hash). 
 * Login will be done through the email field.
 */
return [
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
	
];
 