<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 15.12.2019
 * Time: 23:22
 */

namespace app\Controller;


use app\Application;

class OrderController extends Controller
{
    public function actionCreate()
    {
        $orderService = Application::getInstance()->getService('Order', 'Order');
        return ['id' => $orderService->create((array)$_POST['items'])];
    }

    public function actionPaid()
    {
        $id = (int)$_POST['id'];
        $amount = (int)$_POST['amount'];

        $orderService = Application::getInstance()->getService('Order', 'Order');
        $orderService->paid($id, $amount);

        return [];
    }
}