<?php
/**
 * Created by PhpStorm.
 * UserInterface: sven
 * Date: 15.05.14
 * Time: 09:05
 */

namespace Ipunkt\Auth\models;


use Illuminate\Auth\Reminders\RemindableInterface;
use Ipunkt\Auth\DummyUserPermissionTrait;
use Ipunkt\Auth\EloquentUserTrait;
use Ipunkt\Permissions\HasPermissionTrait;
use Ipunkt\Permissions\CanTrait;

class EloquentUser extends \Eloquent implements UserInterface {
    use EloquentUserTrait;
    use DummyUserPermissionTrait;
    use CanTrait;
    use HasPermissionTrait;

    protected $permission_checker_path = 'Ipunkt\Auth\PermissionChecker\UserPermissionChecker';

    function __construct() {
        $this->traitSetTable();
    }
}