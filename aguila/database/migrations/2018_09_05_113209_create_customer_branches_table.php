<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rut');
            $table->integer('customer_id');
            $table->integer('zone_id')->nullable();
            $table->integer('seller_id');
            $table->string('code');
            $table->string('label');
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();
            $table->integer('commune_id');
            $table->string('address');
            $table->integer('street_id')->nullable();
            $table->string('build_number')->nullable();
            $table->string('tower')->nullable();
            $table->string('floor')->nullable();
            $table->string('department_number')->nullable();
            $table->string('attendant')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->unique(['customer_id','code','rut'], 'unique_customer_branchs_code');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade');
            $table->foreign('street_id')->references('id')->on('streets')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_branches');
    }
}
