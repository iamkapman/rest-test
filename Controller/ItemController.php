<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 21:31
 */

namespace app\Controller;


use app\Application;

class ItemController extends Controller
{
    public function actionGenerateItems()
    {
        $itemService = Application::getInstance()->getService('Item', 'Item');
        for ($i = 0; $i < 10; $i++) {
            $itemService->create("Item #{$i}", mt_rand(1000, 10000));
        }
        return [];
    }

    public function actionGet()
    {
        $itemService = Application::getInstance()->getService('Item', 'Item');
        return $itemService->findAll();
    }
}