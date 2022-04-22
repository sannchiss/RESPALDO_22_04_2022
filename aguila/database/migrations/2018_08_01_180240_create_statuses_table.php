<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('system_area_id')->index();
            $table->string('code');
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();

            $table->unique(['system_area_id','code'], 'unique_statuses_code');
            $table->foreign('system_area_id')->references('id')->on('system_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
