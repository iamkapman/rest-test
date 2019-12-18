<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 20:47
 */

namespace app\Domain\Order\Entities;


use app\Domain\Item\Entities\Item;
use app\Specification\BaseEntity;

class Position extends BaseEntity
{
    protected $item;
    protected $quantity;

    /**
     * Position constructor.
     * @param $item
     * @param $quantity
     */
    public function __construct(Item $item, int $quantity = 1)
    {
        $this->item = $item;
        $this->quantity = $quantity;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}