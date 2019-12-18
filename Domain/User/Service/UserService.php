<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 20:22
 */

namespace app\Domain\User\Service;


use app\Domain\User\Entities\User;
use app\Domain\User\Repository\UserRepositoryInterface;
use app\Specification\ServiceInterface;

class UserService implements ServiceInterface
{
    /** @var UserRepositoryInterface */
    protected $userRepository;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $userId
     * @return User|null
     */
    public function get($userId)
    {
        $user = $this->userRepository->findById($userId);

        return $user;
    }
}