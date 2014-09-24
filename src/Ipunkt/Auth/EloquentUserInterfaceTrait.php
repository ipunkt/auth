<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 19.05.14
 * Time: 13:43
 */

namespace Ipunkt\Auth;
use Illuminate\Support\Facades\Config;


/**
 * Class EloquentUserInterfaceTrait
 * @package Ipunkt\Auth
 *
 * Simple implementation of the UserInterface for an eloquent model.
 */
trait EloquentUserInterfaceTrait {
    /**
     * This should be called by the constructor, or for simplicity it should replace the
     * constructor by default, but this errors out
     */
    public function traitSetTable() {
        $table_name = Config::get('auth::user table.table name');
        $this->setTable($table_name);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}