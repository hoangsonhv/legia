<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToWarehouseExportProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_export_products', function (Blueprint $table) {
            $table->string('do_day', 255)->after('code')->nullable(true);
            $table->string('hinh_dang', 255)->after('code')->nullable(true);
            $table->string('dia_w_w1', 255)->after('code')->nullable(true);
            $table->string('l_l1', 255)->after('code')->nullable(true);
            $table->string('w2', 255)->after('code')->nullable(true);
            $table->string('l2', 255)->after('code')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_export_product', function (Blueprint $table) {
            //
        });
    }
}
