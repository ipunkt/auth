<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 23.05.14
 * Time: 14:47
 */

namespace Ipunkt\Auth\Repositories;


use Ipunkt\Auth\models\UserInterface;

class EloquentRepository implements RepositoryInterface {
    use EloquentRepositoryTrait;
}