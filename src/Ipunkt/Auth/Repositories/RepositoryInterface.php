<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 23.05.14
 * Time: 14:44
 */

namespace Ipunkt\Auth\Repositories;


use Ipunkt\Auth\models\UserInterface;

interface RepositoryInterface {
    /**
     * Create a new User
     *
     * @return UserInterface
     */
    public function create();

    /**
     * Returns all Users in this repository
     *
     * @return null|UserInterface[]
     */
    public function all();

    /**
     * Attempt to save changes to the user
     *
     * @param UserInterface $user
     * @return boolean
     */
    public function save(UserInterface $user);

    /**
     * @param int $id
     * @return UserInterface
     */
    public function findOrFail($id);

    /**
     * Attempts to delete the user
     * returns true on success,
     * false otherwise
     *
     * @param UserInterface $user
     * @return boolean
     */
    public function delete(UserInterface $user);
}