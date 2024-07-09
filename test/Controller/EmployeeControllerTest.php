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

namespace HyperfTest\Controller;

use HyperfTest\HttpTestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as StatusCodes;

/**
 * @internal
 * @coversNothing
 */
class EmployeeControllerTest extends HttpTestCase
{
    public function testIndex(): void
    {
        /** @var ResponseInterface $response */
        $response = $this->request('GET', '/employees');

        $this->assertEquals(StatusCodes::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }
}
