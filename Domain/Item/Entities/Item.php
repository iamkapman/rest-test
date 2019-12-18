<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 18:58
 */

namespace app\Domain\Item\Entities;


use app\Domain\Money\Specification\Money;
use app\Specification\BaseEntity;

class Item extends BaseEntity
{
    protected $id;
    protected $name;
    protected $price;

    /**
     * Item constructor.
     * @param int $id
     * @param string $name
     * @param Money $price
     */
    public function __construct(int $id, string $name, Money $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['price'] = $this->getPrice()->getAmount();
        return $array;
    }


}