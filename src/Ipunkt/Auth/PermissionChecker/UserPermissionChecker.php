<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 03.06.14
 * Time: 09:43
 */

namespace Ipunkt\Auth\PermissionChecker;


use Illuminate\Auth\UserInterface;
use Ipunkt\Permissions\PermissionChecker\PermissionChecker;

/**
 * Class UserPermissionChecker
 * @package Ipunkt\Simpleauth\PermissionChecker
 *
 * Default PermissionChecker used by the EloquentUser model.
 * Returns true when called upon itself:
 * $user->can('anything', $user); will return true
 * anything else will return false
 */
class UserPermissionChecker extends PermissionChecker {
    /**
     * Check if the given User has permission to do action on this objects assigned model
     *
     * @param UserInterface $object
     * @param string $action
     * @return boolean
     */
    public function checkPermission(UserInterface $object, $action) {
        return $this->getEntity()->getId() == $object->getId();
    }

} 