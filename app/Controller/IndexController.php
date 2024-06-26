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

namespace App\Controller;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;

class IndexController
{
    public function index(Response $response): ResponseInterface
    {
        $data = Db::select('SELECT CURRENT_TIMESTAMP()');

        return $response->json(['status' => 'ok', 'data' => $data])
            ->withStatus(200);
    }
}
