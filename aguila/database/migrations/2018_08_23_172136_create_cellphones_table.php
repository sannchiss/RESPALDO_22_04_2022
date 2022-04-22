<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCellphonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('cellphones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label')->unique();
            $table->string('imei',16)->unique();
            $table->string('imsi',16)->unique();
            $table->string('phone_number',16)->nullable();
            $table->integer('phone_operator_id')->index();
            $table->integer('cellphone_platform_id')->index();
            $table->string('os_version')->nullable();
            $table->integer('office_id')->index();
            $table->integer('status_id')->index();
            $table->integer('employee_id')->nullable()->index();;
            $table->integer('auxiliary_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('phone_operator_id')->references('id')->on('phone_operators')->onDelete('cascade');
            $table->foreign('cellphone_platform_id')->references('id')->on('cellphone_platforms')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('auxiliary_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cellphones');
    }
}
