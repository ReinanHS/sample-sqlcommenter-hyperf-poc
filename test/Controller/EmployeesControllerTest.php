<?php

namespace HyperfTest\Controller;

use HyperfTest\HttpTestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as StatusCodes;

class EmployeesControllerTest extends HttpTestCase
{

    public function testIndex(): void
    {
        /** @var ResponseInterface $response */
        $response = $this->request('GET', '/employees');

        $this->assertEquals(StatusCodes::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }
}
