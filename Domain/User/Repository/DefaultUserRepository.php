<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 19:36
 */

namespace app\Domain\User\Repository;


use app\Domain\User\Entities\User;

class TestUserRepository implements UserRepositoryInterface
{
    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        if ($id === 1) {
            return new User(1, 'admin');
        }

        return null;
    }
}