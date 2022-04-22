<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('label')->unique();
            $table->string('plate_number')->unique();
            $table->integer('vehicle_type_id')->index();
            $table->integer('carrier_id')->index();
            $table->integer('office_id')->index();
            $table->integer('status_id')->index();
            $table->integer('employee_id')->nullable();
            $table->integer('vehicle_condition_id')->default(1)->index();
            $table->timestamps();

            $table->foreign('vehicle_condition_id')->references('id')->on('vehicle_conditions')->onDelete('cascade');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types')->onDelete('cascade');
            $table->foreign('carrier_id')->references('id')->on('carriers')->onDelete('cascade');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
