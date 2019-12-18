<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 19:13
 */

namespace app\Domain\Order\Entities;


use app\Domain\Order\Specification\Status;
use app\Domain\Order\ValueObject\NewStatus;
use app\Domain\User\Entities\User;
use app\Specification\BaseEntity;

class Order extends BaseEntity
{
    /** @var int */
    protected $id;

    /** @var User */
    protected $user;

    /** @var Status */
    protected $status;

    /** @var Position[] */
    protected $positions;

    /**
     * Order constructor.
     * @param $id
     * @param $user
     */
    public function __construct(int $id, User $user)
    {
        $this->id = $id;
        $this->user = $user;
        $this->status = new NewStatus();
        $this->positions = [];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @return Position[]
     */
    public function getPositions(): array
    {
        return $this->positions;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @param Position $position
     */
    public function addPosition(Position $position)
    {
        $this->positions[] = $position;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        $amount = 0;
        foreach ($this->getPositions() as $position) {
            $amount += ($position->getItem()->getPrice()->getAmount() * $position->getQuantity());
        }
        return $amount;
    }
}