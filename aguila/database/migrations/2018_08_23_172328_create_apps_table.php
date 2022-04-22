<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('label');
            $table->integer('cellphone_platform_id');
            $table->string('latest_version_name');
            $table->string('previus_version_name');
            $table->integer('latest_version_code');
            $table->integer('previus_version_code');
            $table->boolean('active_update')->default(false);
            $table->string('icon')->nullable();
            $table->timestamps();

            $table->foreign('cellphone_platform_id')->references('id')->on('cellphone_platforms')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
