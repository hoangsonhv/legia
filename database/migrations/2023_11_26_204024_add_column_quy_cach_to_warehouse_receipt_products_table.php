<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnQuyCachToWarehouseReceiptProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_receipt_products', function (Blueprint $table) {
            $table->string('kich_thuoc');
            $table->string('quy_cach');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_receipt_products', function (Blueprint $table) {
            //
        });
    }
}