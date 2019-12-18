<?php
/**
 * Created by PhpStorm.
 * User: Kapman
 * Date: 14.12.2019
 * Time: 21:42
 */

namespace app;

use app\Controller\Controller;
use app\Controller\ItemController;
use app\Controller\OrderController;
use app\Domain\User\Entities\User;
use app\Specification\ServiceInterface;
use app\Specification\SqlRepositoryInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Application
{
    /** @var Application */
    private static $instance = null;

    /** @var \PDO */
    protected $connection;

    /** @var ServiceInterface[] */
    protected $services;

    /** @var User */
    protected $user;

    /**
     * Инициализация маршрутизатора
     * @return array
     */
    public function routerInit()
    {
        $routes = new RouteCollection();

        // Генератор 10 новых товаров
        $route = new Route('/item/generate/', ['_controller' => ItemController::class, '_action' => 'generateItems']);
        $route->setMethods(['POST']);
        $routes->add('item-generate-items', $route);

        // Возврат списка всех товаров
        $route = new Route('/item/get/', ['_controller' => ItemController::class, '_action' => 'get']);
        $routes->add('item-get', $route);

        // Создание заказа
        $route = new Route('/order/create/', ['_controller' => OrderController::class, '_action' => 'create']);
        $route->setMethods(['POST']);
        $routes->add('order-create', $route);

        // Оплата заказа
        $route = new Route('/order/paid/', ['_controller' => OrderController::class, '_action' => 'paid']);
        $route->setMethods(['POST']);
        $routes->add('order-paid', $route);

        $context = new RequestContext('/', $_SERVER['REQUEST_METHOD']);
        $matcher = new UrlMatcher($routes, $context);

        return $matcher->match($_SERVER['REQUEST_URI']);
    }

    /**
     * Выполняет действие (метод) в контроллере
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function runAction(array $parameters)
    {
        if (!class_exists($parameters['_controller'])) {
            throw new \Exception('Class Not Found', 404);
        }

        /** @var Controller $controller */
        $controller = new $parameters['_controller'];

        $action = 'action' . ucfirst($parameters['_action']);
        if (!method_exists($controller, $action)) {
            throw new \Exception('Action Not Found', 404);
        }

        return $controller->callMethod($action, $parameters);
    }

    /**
     * Старт приложения
     */
    public function start()
    {
        try {
            $parameters = $this->routerInit();
            $result = $this->runAction($parameters);
            $this->outputAsJson($result);
        } catch (\Exception $exception) {
            $this->outputAsJson(['message' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Выводит входные данные в формате json
     * @param array $data
     * @param int $code
     */
    public function outputAsJson(array $data, int $code = 200)
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode([
            'data' => $data,
            'code' => $code,
        ]);
        exit();
    }

    /**
     * Application constructor.
     */
    private function __construct()
    {
        $this->connection = new \PDO('mysql:host=mysql;dbname=test', 'test', 'test');
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $userService = $this->getService('User', 'User');
        $this->user = $userService->get(1);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    /**
     * @return Application
     */
    public static function getInstance()
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        return new self;
    }

    /**
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    /**
     * @return mixed
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Инициализирует сервис
     * @param string $aggregateName
     * @param string $serviceName
     * @return ServiceInterface
     */
    public function getService(string $aggregateName, string $serviceName): ServiceInterface
    {
        $aggregatePath = "app\\Domain\\{$aggregateName}";
        $servicePath = "{$aggregatePath}\\Service\\{$serviceName}Service";

        // Первая инициализация
        if (!isset($this->services[$servicePath])) {
            $repositoryPrefix = "{$aggregatePath}\\Repository";
            $repositoryPath = "{$repositoryPrefix}\\Sql{$serviceName}Repository";
            if (class_exists($repositoryPath) && in_array(SqlRepositoryInterface::class, class_implements($repositoryPath))) {
                $repository = new $repositoryPath(Application::getConnection());
            } else {
                $defaultRepository = "{$repositoryPrefix}\\Default{$serviceName}Repository";
                $repository = new $defaultRepository;
            }

            $this->services[$servicePath] = new $servicePath($repository);
        }

        return $this->services[$servicePath];
    }
}