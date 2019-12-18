<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 15.12.2019
 * Time: 21:58
 */

namespace app\Domain\Order\Service;


use app\Application;
use app\Domain\Order\Entities\Order;
use app\Domain\Order\Entities\Position;
use app\Domain\Order\Repository\OrderRepositoryInterface;
use app\Domain\Order\ValueObject\PaidStatus;
use app\Specification\ServiceInterface;
use GuzzleHttp\Client;

class OrderService implements ServiceInterface
{
    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /**
     * OrderService constructor.
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $items
     * @return int
     * @throws \Exception
     */
    public function create(array $items): int
    {
        if (!count($items)) {
            throw new \Exception("Empty items", 403);
        }

        $items = array_map('intval', $items);

        $order = new Order(0, Application::getInstance()->getUser());

        $itemService = Application::getInstance()->getService('Item', 'Item');
        foreach ($items as $itemId) {
            $item = $itemService->findById($itemId);
            if ($item === null) {
                throw new \Exception("Not Found #{$itemId}", 404);
            }

            $order->addPosition(new Position($item));
        }

        $orderId = $this->orderRepository->insert($order);

        return $orderId;
    }

    /**
     * @param int $id
     * @param int $amount
     * @return array
     * @throws \Exception
     */
    public function paid(int $id, int $amount)
    {
        $order = $this->orderRepository->findById($id);
        if ($order === null) {
            throw new \Exception('Order Not Found', 404);
        }

        if ($order->getStatus()->getName() !== 'new') {
            throw new \Exception('Only a new order can be paid', 403);
        }

        if ($order->getAmount() !== $amount) {
            throw new \Exception('Amount does not match', 403);
        }

        if (!$this->checkCodeForPaidOrder()) {
            throw new \Exception('Checked URL returned invalid code', 403);
        }

        $order->setStatus(new PaidStatus());
        $this->orderRepository->updateStatus($order);

        return [];
    }

    /**
     * @return bool
     */
    public function checkCodeForPaidOrder(): bool
    {
        $client = new Client([
            'base_uri' => 'https://ya.ru/',
        ]);
        $response = $client->request('GET', '/');

        return $response->getStatusCode() === 200;
    }
}