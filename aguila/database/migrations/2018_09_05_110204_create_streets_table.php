<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('street_type_id');
            $table->integer('commune_id');
            $table->string('label');
            $table->timestamps();

            $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
            $table->foreign('street_type_id')->references('id')->on('street_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streets');
    }
}
