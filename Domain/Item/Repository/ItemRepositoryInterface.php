<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 19:45
 */

namespace app\Domain\Item\Repository;


use app\Domain\Item\Entities\Item;

interface ItemRepositoryInterface
{
    public function findById(int $id): ?Item;
    public function findAll(): array;
    public function insert(Item $item);
}