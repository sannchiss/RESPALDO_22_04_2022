<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentGpsStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_gps_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gps_device_id')->unique();
            $table->integer('employee_id')->nullable();
            $table->integer('vehicle_id');
            $table->double('lat');
            $table->double('lon');
            $table->double('speed');
            $table->smallInteger('heading');
            $table->integer('miliage');
            $table->smallInteger('gps_signal');
            $table->smallInteger('phone_signal');
            $table->smallInteger('ignition_status');

            $table->datetime('date_time');
            $table->timestamps();

            $table->foreign('gps_device_id')->references('id')->on('gps_devices')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('current_gps_statuses');
    }
}
