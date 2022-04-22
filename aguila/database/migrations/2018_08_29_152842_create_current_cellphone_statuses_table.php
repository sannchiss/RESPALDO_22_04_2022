<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentCellphoneStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_cellphone_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cellphone_id')->unique();
            $table->integer('employee_id')->nullable();
            $table->integer('auxiliary_id')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->double('lat');
            $table->double('lon');
            $table->double('battery')->nullable(); //porcentage
            $table->double('precision')->nullable();
            $table->double('speed')->nullable();
            $table->double('signal')->nullable(); //cellphone
            $table->double('wifi_signal')->nullable();
            $table->double('wifi_strength')->nullable();
            $table->string('wifi_mac')->nullable();
            $table->double('capacity_storage_mb')->nullable();
            $table->double('available_storage_mb')->nullable();

            $table->datetime('date_time');
            $table->timestamps();

            $table->foreign('cellphone_id')->references('id')->on('cellphones')->onDelete('cascade');
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
        Schema::dropIfExists('current_cellphone_statuses');
    }
}
