<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGpsDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gps_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->nullable();
            $table->integer('gps_type_id')->index();
            $table->integer('status_id')->index();
            $table->integer('phone_operator_id')->index();
            $table->string('imei',16)->unique();
            $table->string('imsi',16)->unique();
            $table->string('phone_number',16)->unique();
            $table->string('version');
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('gps_type_id')->references('id')->on('gps_types')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('phone_operator_id')->references('id')->on('phone_operators')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gps_devices');
    }
}
