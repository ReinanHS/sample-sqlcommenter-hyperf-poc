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
use App\Processor\LogCustomProcessor;
use Monolog\Formatter\GoogleCloudLoggingFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

return [
    'default' => [
        'handler' => [
            'class' => StreamHandler::class,
            'constructor' => [
                'stream' => 'php://stdout',
                'level' => Level::Debug,
            ],
        ],
        'formatter' => [
            'class' => GoogleCloudLoggingFormatter::class,
            'constructor' => [
                'includeStacktraces' => true,
            ],
        ],
        'processors' => [
            new LogCustomProcessor(),
        ],
    ],
    'test' => [
        'handler' => [
            'class' => StreamHandler::class,
            'constructor' => [
                'stream' => BASE_PATH . '/runtime/logs/hyperf.log',
                'level' => Level::Debug,
            ],
        ],
        'formatter' => [
            'class' => LineFormatter::class,
            'constructor' => [
                'includeStacktraces' => true,
            ],
        ],
    ],
];
