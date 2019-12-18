<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 20:44
 */

namespace app\Domain\Order\Repository;


use app\Application;
use app\Domain\Order\Entities\Order;
use app\Domain\Order\Entities\Position;
use app\Domain\Order\Specification\Status;
use app\Specification\SqlRepositoryInterface;

class SqlOrderRepository implements OrderRepositoryInterface, SqlRepositoryInterface
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

    public function insert(Order $order)
    {
        $this->db->beginTransaction();

        $statement = $this->db->prepare('INSERT INTO `order` (`user`, `status`) VALUES (:user, :status)');
        $statement->bindValue(':user', $order->getUser()->getId(), \PDO::PARAM_INT);
        $statement->bindValue(':status', $order->getStatus()->getName());
        $statement->execute();
        $orderId = $this->db->lastInsertId();

        $statement = $this->db->prepare('INSERT INTO `order_position` (`order`, `item`, `quantity`) VALUES (:order, :item, :quantity)');
        foreach ($order->getPositions() as $position) {
            $statement->bindValue(':order', $orderId, \PDO::PARAM_INT);
            $statement->bindValue(':item', $position->getItem()->getId(), \PDO::PARAM_INT);
            $statement->bindValue(':quantity', $position->getQuantity(), \PDO::PARAM_INT);
            $statement->execute();
        }

        $this->db->commit();

        return $orderId;
    }

    public function updateStatus(Order $order)
    {
        $this->db->beginTransaction();

        $statement = $this->db->prepare('UPDATE `order` SET `status` = :status WHERE `id` = :id');
        $statement->bindValue(':status', $order->getStatus()->getName());
        $statement->bindValue(':id', $order->getId());
        $statement->execute();

        $this->db->commit();
    }

    public function findById(int $id): ?Order
    {
        $statement = $this->db->prepare('SELECT `id`, `user`, `status` FROM `order` WHERE `id` = :id');
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $order = new Order($row['id'], Application::getInstance()->getService('User', 'User')->get($row['user']));

        $status = 'app\\Domain\\Order\\ValueObject\\' . ucfirst($row['status']) . 'Status';
        if (!class_exists($status)) {
            $status = 'app\\Domain\\Order\\ValueObject\\NewStatus';
        }
        $order->setStatus(new $status());

        // Позиции
        $itemService = Application::getInstance()->getService('Item', 'Item');
        $statement = $this->db->prepare('SELECT `item`, `quantity` FROM `order_position` WHERE `order` = :id');
        $statement->bindValue(':id', $order->getId(), \PDO::PARAM_INT);
        $statement->execute();

        while($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $item = $itemService->findById($row['item']);
            $order->addPosition(new Position($item, $row['quantity']));
        }

        return $order;
    }
}