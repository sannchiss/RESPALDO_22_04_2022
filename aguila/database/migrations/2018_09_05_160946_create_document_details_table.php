<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //Se agrego barcode por que el wms va separar que cantidades de productos van con un bulto
    public function up()
    {
        Schema::create('document_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id');
            $table->integer('status_id');
            $table->integer('status_reason_id')->nullable();
            $table->integer('product_id');
            $table->double('quantity');
            $table->double('quantity_accepted')->nullable();
            $table->double('quantity_rejected')->nullable();
            $table->integer('row_order')->default(0);
            $table->string('package_barcode')->nullable()->index();
            $table->string('observation')->nullable();

            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('status_reason_id')->references('id')->on('status_reasons')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_details');
    }
}
