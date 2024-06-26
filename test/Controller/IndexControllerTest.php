<?php

namespace HyperfTest\Controller;

use HyperfTest\HttpTestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as StatusCodes;

class IndexControllerTest extends HttpTestCase
{
    public function testTheApplicationReturnSuccessfulResponse(): void
    {
        /** @var ResponseInterface $response */
        $response = $this->request('GET', '/');

        $this->assertEquals(StatusCodes::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }

    public function testTheApplicationTryCallable(): void
    {
        /** @var ResponseInterface $response */
        $response = $this->request('GET', '/callable');

        $this->assertEquals(StatusCodes::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }
}
