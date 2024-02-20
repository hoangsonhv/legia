<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForQCToManufactureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manufactures', function (Blueprint $table) {
            $table->addColumn('tinyint', 'qc_check')->default(1)->after('is_completed');
        });
        Schema::table('manufacture_details', function (Blueprint $table) {
            $table->addColumn('integer', 'error_quantity')->default(0)->after('reality_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
