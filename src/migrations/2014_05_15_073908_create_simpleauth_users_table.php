<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSimpleauthUsersTable
 *
 * create a user table based on the current config values of auth.
 * IMPORTANT: Changing the config values after the migration will break the compatibility between the table and the
 *              EloquentUserWithRolesTrait
 */
class CreateSimpleauthUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $config_values =  Config::get('auth::user table');

		Schema::create($config_values['table name'], function(Blueprint $table) use ($config_values)
		{
			$table->increments('id');
            $table->string('email')->unique();
            foreach($config_values['extra fields'] as $extra_field) {

                // 'do not migrate' allows for managing the value with auth interface but adding it to the
                // database manualy through publishing this migration or making a new one.
                if(! array_key_exists('do not migrate', $extra_field) ) {
                    $type = $extra_field['database type'];
                    $field = $table->$type($extra_field['name']);
                    if(! array_key_exists('not null', $extra_field))
                        $field->nullable();
                    if(array_key_exists('unique', $extra_field))
                        $field->unique();
                }
            }
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->boolean('force_password_reset')->default(false);
            $table->boolean('enabled')->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        $config_values =  Config::get('auth::user table');
		Schema::drop($config_values['table name']);
	}

}
