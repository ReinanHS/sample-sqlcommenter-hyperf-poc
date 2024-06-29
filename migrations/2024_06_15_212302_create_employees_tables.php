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
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateEmployeesTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->char('dept_no', 4)->primary();
            $table->string('dept_name', 40)->unique();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->integer('emp_no')->primary();
            $table->date('birth_date');
            $table->string('first_name', 14);
            $table->string('last_name', 16);
            $table->enum('gender', ['M', 'F']);
            $table->date('hire_date');
        });

        Schema::create('dept_emp', function (Blueprint $table) {
            $table->integer('emp_no');
            $table->char('dept_no', 4);
            $table->date('from_date');
            $table->date('to_date');
            $table->primary(['emp_no', 'dept_no']);
            $table->foreign('emp_no')->references('emp_no')->on('employees')->onDelete('cascade');
            $table->foreign('dept_no')->references('dept_no')->on('departments')->onDelete('cascade');
        });

        Schema::create('dept_manager', function (Blueprint $table) {
            $table->integer('emp_no');
            $table->char('dept_no', 4);
            $table->date('from_date');
            $table->date('to_date');
            $table->primary(['emp_no', 'dept_no']);
            $table->foreign('emp_no')->references('emp_no')->on('employees')->onDelete('cascade');
            $table->foreign('dept_no')->references('dept_no')->on('departments')->onDelete('cascade');
        });

        Schema::create('salaries', function (Blueprint $table) {
            $table->integer('emp_no');
            $table->integer('salary');
            $table->date('from_date');
            $table->date('to_date');
            $table->primary(['emp_no', 'from_date']);
        });

        Schema::create('titles', function (Blueprint $table) {
            $table->integer('emp_no');
            $table->string('title', 50);
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->primary(['emp_no', 'title', 'from_date']);
        });

        Schema::create('expected_values', function (Blueprint $table) {
            $table->string('table_name', 30)->primary();
            $table->integer('recs');
            $table->string('crc_sha', 100);
            $table->string('crc_md5', 100);
        });

        Schema::create('found_values', function (Blueprint $table) {
            $table->string('table_name', 30)->primary();
            $table->integer('recs');
            $table->string('crc_sha', 100);
            $table->string('crc_md5', 100);
        });

        Schema::create('tchecksum', function (Blueprint $table) {
            $table->char('chk', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tchecksum');
        Schema::dropIfExists('found_values');
        Schema::dropIfExists('expected_values');
        Schema::dropIfExists('titles');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('dept_manager');
        Schema::dropIfExists('dept_emp');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
    }
}
