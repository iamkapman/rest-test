<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 21:31
 */

namespace app\Controller;


use app\Application;
use app\Domain\Item\Repository\SqlItemRepository;
use app\Domain\Item\Service\ItemService;

class MainController extends Controller
{
    public function actionStart()
    {
        $itemService = new ItemService(new SqlItemRepository(Application::getInstance()->getConnection()));
        for ($i = 0; $i < 10; $i++) {
            $itemService->create("Item #{$i}", mt_rand(1000, 10000));
        }
    }
}