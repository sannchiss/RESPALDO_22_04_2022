<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('geofence_type_id')->unsigned()->index();
            $table->string('code');
            $table->string('label');
            $table->double('lat');
            $table->double('lon');
            $table->text('path');
            $table->string('color');
            $table->double('radius');
            $table->timestamps();

            $table->foreign('geofence_type_id')->references('id')->on('geofence_types');
        });

        \DB::statement("SELECT AddGeometryColumn('zones', 'geom', 4326, 'POLYGON', 2)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zones');
    }
}
