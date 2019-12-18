<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 17:52
 */

namespace app\Domain\User\Entities;


use app\Specification\BaseEntity;

class User extends BaseEntity
{
    /** @var int ID пользователя */
    protected $id;

    /** @var string Логин пользователя */
    protected $login;

    /**
     * User constructor.
     * @param int $id
     * @param string $login
     */
    public function __construct(int $id, string $login)
    {
        $this->id = $id;
        $this->login = $login;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }
}