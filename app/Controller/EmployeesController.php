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

use Hyperf\Collection\Collection;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;

class EmployeesController
{
    public function __invoke(Response $response): ResponseInterface
    {
        $data = $this->getEmployees();

        return $response->json(['status' => 'ok', 'data' => $data])
            ->withStatus(200);
    }

    private function getEmployees(): Collection
    {
        return Db::table('employees as e')
            ->join('dept_emp as de', 'e.emp_no', '=', 'de.emp_no')
            ->join('departments as d', 'de.dept_no', '=', 'd.dept_no')
            ->leftJoin('dept_manager as dm', 'e.emp_no', '=', 'dm.emp_no')
            ->leftJoin('salaries as s', function ($join) {
                $join->on('e.emp_no', '=', 's.emp_no')
                    ->where('s.to_date', '=', Db::raw('(SELECT MAX(to_date) FROM salaries WHERE emp_no = e.emp_no)'));
            })
            ->leftJoin('titles as t', function ($join) {
                $join->on('e.emp_no', '=', 't.emp_no')
                    ->where('t.to_date', '=', Db::raw('(SELECT MAX(to_date) FROM titles WHERE emp_no = e.emp_no)'));
            })
            ->select(
                'e.emp_no',
                'e.first_name',
                'e.last_name',
                'd.dept_name',
                'dm.from_date as manager_from_date',
                'dm.to_date as manager_to_date',
                's.salary',
                't.title'
            )
            ->where('e.hire_date', '>', '2000-01-01')
            ->where('d.dept_name', '=', 'Sales')
            ->orderBy('e.last_name')
            ->orderBy('e.first_name')
            ->get();
    }
}
