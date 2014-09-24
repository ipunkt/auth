<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 26.05.14
 * Time: 11:08
 */

namespace Ipunkt\Auth;


/**
 * Class DummyUserPermissionTrait
 * @package Ipunkt\Simpleauth
 *
 * This is a dummy implementation of the UserWithRolesInterface, only granting access to edit the
 * users own profile.
 */
trait DummyUserPermissionTrait {
    /**
     * Allows permission to edit the users own profile.
     *
     * @param $permission_string
     * @return bool
     */
    public function hasPermission($permission_string) {
        $permission = false;

        $permission = ($permission_string == 'user.'.$this->getId().'.edit');

        return $permission;
    }
} 