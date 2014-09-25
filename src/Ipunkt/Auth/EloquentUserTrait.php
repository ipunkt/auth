<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 15.05.14
 * Time: 09:11
 */

namespace Ipunkt\Auth;


use Config;
use Hash;
use Ipunkt\Auth\EloquentRemindableTrait;
use Ipunkt\Auth\EloquentUserInterfaceTrait;
use Ipunkt\Auth\models\UserInterface;
use Validator;

/**
 * Class EloquentUserWithRolesTrait
 * @package Ipunkt\Auth\models
 *
 * Simple implementation of the Ipunkt\Auth\UserInterface interface for an eloquent model.
 */
trait EloquentUserTrait {
    use EloquentRemindableTrait;
    use EloquentUserInterfaceTrait;

    /**
     * @var
     */
    protected $validation_errors;

    /**
     * @param $value
     */
    public function setIdentifier($value) {
        $identifier_field = Config::get('auth::user_table.login_through_field');
        $this->$identifier_field = $value;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        $identifier_field = Config::get('auth::user_table.login_through_field');
        return $this->$identifier_field;
    }

    /**
     * @param $value
     */
    public function setEmail($value) {
        $this->email = $value;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $value
     */
    public function setPassword($value) {
        $this->force_password_reset = false;
        $this->password = Hash::make($value);
    }

    /**
     * @param $field
     * @param $value
     */
    public function setExtra($field, $value) {
        $this->$field = $value;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getExtra($field) {
        return $this->$field;
    }

    /**
     * set the password reset flag to true
     */
    public function setForcePasswordReset()
    {
        $this->force_password_reset = true;
    }

    /**
     * return the current status of the password reset flag
     *
     * @return boolean
     */
    public function getForcePasswordReset()
    {
        return $this->force_password_reset;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->getKey();
    }

    public function isSuperuser() {
        return $this->superuser;
    }

    public function setEnabled($yn) {
        $this->enabled = $yn ? true : false;
    }

    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * @return bool
     */
    public function validate() {
        $table_config = Config::get('auth::user table');

        $rules = [];
        $rules['email'] = 'required|email';

        $data = [];
        $data['email'] = $this->getEmail();

        foreach($table_config['extra fields'] as $extra_field) {
            $field_name = $extra_field['name'];
            $rules[ $field_name ] = $extra_field['validation rules'];
            $data[ $field_name ] = $this->getExtra($field_name);
        }

        $validator = Validator::make($data, $rules);

        $success = $validator->passes();

        $this->validation_errors = $validator->errors();

        return $success;
    }

    /**
     * @return mixed
     */
    public function validationErrors() {
        return $this->validation_errors;
    }

    public function isEqual(UserInterface $user) {
        return ($this->getId() == $user->getId());
    }

    /**
     * @return mixed
     */
    public function save() {
        return parent::save();
    }

}