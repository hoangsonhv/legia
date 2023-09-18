<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarehouseProductWarehouseGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_groups', function (Blueprint $table) {
            $table->string('warehouse_product')->nullable();
            $table->string('warehouse_ingredient')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_groups', function (Blueprint $table) {
            $table->dropColumn('warehouse_product');
            $table->dropColumn('warehouse_ingredient');
        });
    }
}
