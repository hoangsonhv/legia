<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColCoIdWarehouseReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_receipts', function (Blueprint $table) {
            $table->integer('co_id')->nullable()->index();
        });

        Schema::table('warehouse_exports', function (Blueprint $table) {
            $table->integer('co_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_receipts', function (Blueprint $table) {
            $table->dropColumn('co_id');
        });

        Schema::table('warehouse_exports', function (Blueprint $table) {
            $table->dropColumn('co_id');
        });
    }
}
