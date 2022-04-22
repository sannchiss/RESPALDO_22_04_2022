<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('rut');
            $table->string('dv',5);
            $table->string('name');
            $table->string('lastname');
            $table->string('phone');
            $table->integer('status_id');
            $table->integer('employee_type_id')->index();
            $table->boolean('has_access')->default(false);
            $table->integer('office_id');
            $table->timestamps();

            $table->unique(['employee_type_id','code','office_id'], 'unique_employees_code');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreign('employee_type_id')->references('id')->on('employee_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
