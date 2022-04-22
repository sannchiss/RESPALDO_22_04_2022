<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->integer('route_id')->index();
            $table->datetime('document_date');
            $table->integer('document_type_id')->nullable()->index(); //;
            $table->string('order_number');
            $table->string('order_barcode');
            $table->integer('status_id')->index();
            $table->integer('status_reason_id')->nullable()->index();
            $table->datetime('processed_date')->nullable();
            $table->integer('seller_id')->index();
            $table->string('received_by')->nullable();
            $table->integer('packages');
            $table->integer('products');
            $table->integer('units');
            $table->integer('row_order')->default(0);
            $table->integer('real_row_order')->nullable();
            $table->integer('origin_office_id')->nullable();
            $table->integer('rejected_packages')->default(0);
            $table->integer('rejected_products')->default(0);
            $table->integer('rejected_units')->default(0);
            $table->integer('customer_branch_id')->index();
            $table->string('observation')->nullable();
            $table->integer('origin_document_id')->nullable();



            $table->foreign('origin_office_id')->references('id')->on('offices')->onDelete('cascade');
            //$table->foreign('document_type_id')->references('id')->on('document_types')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('status_reason_id')->references('id')->on('status_reasons')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('customer_branch_id')->references('id')->on('customer_branches')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
