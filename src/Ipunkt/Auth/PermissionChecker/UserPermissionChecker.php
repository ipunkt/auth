<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 03.06.14
 * Time: 09:43
 */

namespace Ipunkt\Auth\PermissionChecker;


use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Config;
use Ipunkt\Permissions\CanInterface;
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
	 * @param CanInterface $object
	 * @param string $action
	 * @return bool
	 */
	function checkPermission(CanInterface $object, $action) {
		$permission = false;
		
		if(in_array($action, Config::get('auth::user_actions', [])))
			$permission = $this->getEntity()->getId() == $object->getId();
		
		return $permission;
	}


} 