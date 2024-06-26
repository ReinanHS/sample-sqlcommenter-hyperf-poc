<?php

namespace App\Controller;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;

#[AutoController(prefix: 'employee-salary-history')]
class EmployeeSalaryHistoryController
{
    public function getSalaryHistory(HttpResponse $response): ResponseInterface
    {
        $salaryHistory = Db::table('employees as e')
            ->join('dept_emp as de', 'e.emp_no', '=', 'de.emp_no')
            ->join('departments as d', 'de.dept_no', '=', 'd.dept_no')
            ->join('titles as t', 'e.emp_no', '=', 't.emp_no')
            ->join('salaries as s', 'e.emp_no', '=', 's.emp_no')
            ->select(
                'e.emp_no',
                'e.first_name',
                'e.last_name',
                'd.dept_name',
                't.title',
                's.salary',
                's.from_date as salary_from_date',
                's.to_date as salary_to_date'
            )
            ->whereRaw('s.from_date >= de.from_date')
            ->whereRaw('s.to_date <= de.to_date')
            ->whereRaw('s.from_date >= t.from_date')
            ->where(function($query) {
                $query->whereRaw('s.to_date <= t.to_date')
                    ->orWhereNull('t.to_date');
            })
            ->orderBy('e.emp_no')
            ->orderBy('s.from_date')
            ->get();

        return $response->json(['status' => 'ok', 'data' => $salaryHistory])
            ->withStatus(200);
    }
}