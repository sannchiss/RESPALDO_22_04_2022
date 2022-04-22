<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('vehicle_id');
            $table->integer('driver_id');
            $table->integer('auxiliary_id')->nullable();
            $table->integer('status_id');
            $table->integer('status_reason_id')->nullable();
            $table->datetime('scheduled_date');
            $table->datetime('departure_date')->nullable(); //fecha salida real
            $table->integer('office_id');
            $table->smallInteger('viewing_time');
            $table->integer('loaded_packages'); //total de bultos cargados
            $table->integer('loaded_products'); //total de diferentes tipos de productos cargados
            $table->integer('loaded_units'); //total de unidades de productos cargados
            $table->integer('remainder_packages'); //total de bultos que quedan por entregar
            $table->integer('remainder_products'); //total de diferentes tipos de productos que quedan por entregar
            $table->integer('remainder_units'); //total de unidades de productos que quedan por entregar
            $table->string('observation')->nullable();;
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('auxiliary_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('status_reason_id')->references('id')->on('status_reasons')->onDelete('cascade');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}
