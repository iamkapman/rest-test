<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 19:34
 */

namespace app\Domain\User\Repository;


use app\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
}