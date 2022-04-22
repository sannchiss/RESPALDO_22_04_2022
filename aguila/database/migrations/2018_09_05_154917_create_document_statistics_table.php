<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unique();
            $table->datetime('start_travel_date');
            $table->double('start_lat');
            $table->double('start_lon');
            $table->integer('distance_calculated'); //en kilometros
            $table->integer('distance_traveled')->nullable();
            $table->datetime('end_travel_date')->nullable(); //llegada cliente
            $table->double('end_lat');
            $table->double('end_lon');
            $table->datetime('custumer_departure_date')->nullable();
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_statistics');
    }
}
