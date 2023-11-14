<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableBaseWarehouseQuantity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('base_warehouses', function (Blueprint $table) {
            $table->integer('sl_tam')->default(0)->change();
            $table->integer('ton_sl_tam')->default(0)->change();
            $table->integer('sl_cuon')->default(0)->change();
            $table->integer('ton_sl_cuon')->default(0)->change();
            $table->integer('sl_cai')->default(0)->change();
            $table->integer('ton_sl_cai')->default(0)->change();
            $table->integer('sl_cay')->default(0)->change();
            $table->integer('ton_sl_cay')->default(0)->change();
            $table->integer('sl_ton')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
