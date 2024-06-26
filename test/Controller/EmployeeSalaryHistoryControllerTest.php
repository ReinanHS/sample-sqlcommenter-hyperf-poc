<?php

namespace HyperfTest\Controller;

use HyperfTest\HttpTestCase;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response as StatusCodes;

class EmployeeSalaryHistoryControllerTest extends HttpTestCase
{
    public function testGetSalaryHistory(): void
    {
        /** @var ResponseInterface $response */
        $response = $this->request('GET', '/employee-salary-history/getSalaryHistory');

        $this->assertEquals(StatusCodes::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }
}
