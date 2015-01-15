<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 19.05.14
 * Time: 13:26
 */

namespace Ipunkt\Auth\models;


use Illuminate\Auth\Reminders\RemindableInterface;
use Ipunkt\Permissions\HasPermissionInterface;
use Ipunkt\Permissions\CanInterface;

/**
 * Interface UserInterface
 * @package Ipunkt\Simpleauth
 *
 * This interface takes care of all the interaction between register / edit / login interface and the user model.
 * In Eloquent models these calls translate into simple return $this->variable / $this->variable = $value
 */
interface UserInterface extends \Illuminate\Auth\UserInterface, RemindableInterface, CanInterface,
                                HasPermissionInterface {

    /**
     * sets the value of the field which had its name set in auth::login through field
     * Default behaviour: equal to setEmail($value);
     *
     * @param string $value
     * @return string
     */
    public function setIdentifier($value);

    /**
     * returns the value of the field which had its name set in auth::login through field
     * Default behaviour: equal to getEmail();
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Sets the email field to the given value
     *
     * @param string $value
     */
    public function setEmail($value);

    /*
     * Gets the current value of the email field
     *
     * @return string
     */
    public function getEmail();

    /**
     * set a new value to the password field
     * this value will get hashed before storing it.
     *
     * @param string $value
     * @return mixed
     */
    public function setPassword($value);

    /**
     * Get the current value of the extra field with name $field
     *
     * @param string $field
     * @return mixed
     */
    public function getExtra($field);

    /**
     * set the extra field with name $field to the new value $value
     *
     * @param string $field
     * @param mixed $value
     */
    public function setExtra($field, $value);

    /**
     * set the password reset flag to true
     */
    public function setForcePasswordReset();

    /**
     * return the current status of the password reset flag
     *
     * @return boolean
     */
    public function getForcePasswordReset();

    /*
     * Save changes made to this user to the database.
     *
     * @return boolean True if the user was saved, False otherwise
     */
    /**
     * @return bool
     */
    public function save();

    /**
     * Test if the values currently set on this user models are valid.
     * Any errors encountered will be available through calls to validationErrors()
     *
     * @return boolean True if user is valid, false otherwise
     */
    public function validate();

    /**
     * Returns all errors encountered by the last call to validate()
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function validationErrors();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return boolean
     */
    public function isSuperuser();

    /**
     * @return boolean
     */
    public function isEnabled();

    /**
     * sets or resets enabled flag
     *
     * @param boolean $yn
     */
    public function setEnabled($yn);

    /**
     * @return boolean
     */
    public function isEqual(UserInterface $user);
}