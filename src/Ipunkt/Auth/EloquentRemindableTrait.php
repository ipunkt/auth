<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 19.05.14
 * Time: 13:42
 */

namespace Ipunkt\Auth;


use Config;

/**
 * Class EloquentRemindableTrait
 * @package Ipunkt\Simpleauth
 *
 * simple implementation of the RemindableInterface for an eloquent model
 */
trait EloquentRemindableTrait {
    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

} 