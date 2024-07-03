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
use function Hyperf\Support\env;

return [
    'enable' => env('SQLCOMMENTER_ENABLE', true),
    'include' => [
        'framework' => env('SQLCOMMENTER_ENABLE_FRAMEWORK', true),
        'controller' => env('SQLCOMMENTER_ENABLE_CONTROLLER', true),
        'action' => env('SQLCOMMENTER_ENABLE_ACTION', true),
        'route' => env('SQLCOMMENTER_ENABLE_ROUTE', true),
        'application' => env('SQLCOMMENTER_ENABLE_APPLICATION', true),
        'db_driver' => env('SQLCOMMENTER_ENABLE_DB_DRIVER', true),
    ],
];
