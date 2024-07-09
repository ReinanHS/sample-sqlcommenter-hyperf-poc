<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use App\Controller\DepartmentController;
use App\Controller\EmployeeController;
use App\Controller\TchecksumController;
use App\Controller\TitleController;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Response;
use Hyperf\HttpServer\Router\Router;
use Psr\Http\Message\ResponseInterface;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/employees', EmployeeController::class);
Router::addRoute(['GET', 'POST', 'HEAD'], '/departments', DepartmentController::class);
Router::addRoute(['GET', 'POST', 'HEAD'], '/titles', TitleController::class);
Router::addRoute(['GET', 'POST', 'HEAD'], '/tchecksum', TchecksumController::class);

Router::addRoute(['GET', 'POST', 'HEAD'], '/callable', function (Response $response): ResponseInterface {
    $data = Db::select('SELECT CONNECTION_ID()');

    return $response->json(['status' => 'ok', 'data' => $data])->withStatus(200);
});

Router::addRoute(['GET', 'POST', 'HEAD'], '/hello', function (Response $response): ResponseInterface {
    return $response->json(['status' => 'ok', 'data' => 'Hello'])->withStatus(200);
});

Router::get('/favicon.ico', function () {
    return '';
});
