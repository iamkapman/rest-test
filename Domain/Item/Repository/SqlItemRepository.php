<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 20:29
 */

namespace app\Domain\Item\Repository;


use app\Domain\Item\Entities\Item;
use app\Domain\Money\ValueObject\Ruble;
use app\Specification\SqlRepositoryInterface;

class SqlItemRepository implements ItemRepositoryInterface, SqlRepositoryInterface
{
    protected $db;

    /**
     * SqlItemRepository constructor.
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function insert(Item $item)
    {
        $statement = $this->db->prepare('INSERT INTO `item` (`name`, `price`) VALUES (:name, :price)');
        $statement->bindValue(':name', $item->getName());
        $statement->bindValue(':price', $item->getPrice()->getAmount(), \PDO::PARAM_INT);
        $statement->execute();

        return $this->db->lastInsertId();
    }

    public function findById(int $id): ?Item
    {
        return $this->findAll(['id' => $id])[0] ?? null;
    }

    public function findAll(array $conditions = []): array
    {
        $items = [];

        $sql = 'SELECT `id`, `name`, `price` FROM `item`';

        if (count($conditions)) {
            $whereAnd = [];
            foreach ($conditions AS $param => $value) {
                $value = $this->db->quote($value);
                $whereAnd[] = "`{$param}` = {$value}";
            }
            $sql .= ' WHERE ' . implode(',', $whereAnd);
        }

        $result = $this->db->query($sql, \PDO::FETCH_ASSOC);
        foreach ($result as $item) {
            $items[] = new Item($item['id'], $item['name'], new Ruble($item['price']));
        }

        return $items;
    }
}