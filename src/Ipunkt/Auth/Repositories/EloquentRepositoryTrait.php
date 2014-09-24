<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 23.05.14
 * Time: 14:48
 */

namespace Ipunkt\Auth\Repositories;


use Config;
use Ipunkt\Auth\models\EloquentUser;
use Ipunkt\Auth\models\UserInterface;

trait EloquentRepositoryTrait {
    /**
     * Create a new User
     *
     * @return UserInterface
     */
    public function create()
    {
        $model = Config::get('auth.model');
        return new $model();
    }

    /**
     * Attempt to save changes to the user
     *
     * @param UserInterface $user
     * @return boolean
     */
    public function save(UserInterface $user)
    {
        /**
         * @var EloquentUser $user
         */
        return $user->save();
    }

    public function all() {
        $model = Config::get('auth.model');
        return $model::all();
    }

    /**
     * Attempts to delete the user
     * returns true on success,
     * false otherwise
     *
     * @param UserInterface $user
     * @return boolean
     */
    public function delete(UserInterface $user)
    {
        /**
         * @var EloquentUser $user
         */
        return $user->delete();
    }

    /**
     * @param int $id
     * @return UserInterface
     */
    public function findOrFail($id)
    {
        $model = Config::get('auth.model');
        return $model::findOrFail($id);
    }

}