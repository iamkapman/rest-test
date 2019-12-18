<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 19:41
 */

namespace app\Domain\Order\Repository;


use app\Domain\Order\Entities\Order;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;
    public function insert(Order $order);
    public function updateStatus(Order $order);
}