<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWarehouseExportSellProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_export_sell_product', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_export_sell_id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('unit')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('unit_price', 17)->nullable();
            $table->decimal('into_money', 17)->nullable();
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
        Schema::dropIfExists('table_warehouse_export_sell_product');
    }
}
