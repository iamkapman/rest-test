<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 21:33
 */

namespace app\Domain\Item\Service;


use app\Domain\Item\Entities\Item;
use app\Domain\Item\Repository\ItemRepositoryInterface;
use app\Domain\Money\ValueObject\Ruble;
use app\Specification\ServiceInterface;

class ItemService implements ServiceInterface
{
    protected $itemRepository;

    /**
     * ItemService constructor.
     * @param $itemRepository
     */
    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function create(string $name, int $price)
    {
        $item = new Item(0, $name, new Ruble($price));

        return $this->itemRepository->insert($item);
    }

    public function findAll()
    {
        return array_map(function (Item $item) {
            return $item->toArray();
        }, $this->itemRepository->findAll());
    }

    public function findById(int $id)
    {
        return $this->itemRepository->findById($id);
    }
}