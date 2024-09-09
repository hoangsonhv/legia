<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToWarehouseExportSellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_export_sell_product', function (Blueprint $table) {
            $table->string('do_day', 255)->after('quantity')->nullable(true);
            $table->string('tieu_chuan', 255)->after('quantity')->nullable(true);
            $table->string('kich_co', 255)->after('quantity')->nullable(true);
            $table->string('kich_thuoc', 255)->after('quantity')->nullable(true);
            $table->string('chuan_bich', 255)->after('quantity')->nullable(true);
            $table->string('chuan_gasket', 255)->after('quantity')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_export_sell', function (Blueprint $table) {
            //
        });
    }
}
