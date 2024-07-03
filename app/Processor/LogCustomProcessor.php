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

namespace App\Processor;

use Hyperf\Context\ApplicationContext;
use Hyperf\Context\Context;
use Monolog\LogRecord;
use OpenTracing\Span;
use OpenTracing\Tracer;

use function Hyperf\Support\env;

use const OpenTracing\Formats\TEXT_MAP;

class LogCustomProcessor
{
    public function __invoke(LogRecord $record): LogRecord
    {
        $context = [];
        $root = Context::get('tracer.root');

        if ($root instanceof Span) {
            $container = ApplicationContext::getContainer();
            $trace = $container->get(Tracer::class);

            $trace->inject(spanContext: $root->getContext(), format: TEXT_MAP, carrier: $context);
            if ($context) {
                $record['extra'] = array_merge((array) $record['extra'], $this->processLoggingGoogleAPIs($context));
            }
        }

        return $record;
    }

    private function processLoggingGoogleAPIs(array $b3Context): array
    {
        $traceId = str_pad($b3Context['x-b3-traceid'], 32, '0', STR_PAD_LEFT);
        $spanId = str_pad($b3Context['x-b3-spanid'], 16, '0', STR_PAD_LEFT);
        $sampled = $b3Context['x-b3-sampled'] === '1' ? '01' : '00';

        $gcpProjectId = env('GCP_PROJECT_ID', 'demo');

        return [
            'traceId' => $traceId,
            'logging.googleapis.com/spanId' => $spanId,
            'logging.googleapis.com/trace' => "projects/{$gcpProjectId}/traces/{$traceId}",
            'logging.googleapis.com/trace_sampled' => $sampled,
        ];
    }
}
