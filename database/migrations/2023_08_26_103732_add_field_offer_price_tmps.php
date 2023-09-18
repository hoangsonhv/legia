<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOfferPriceTmps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_price_tmps', function (Blueprint $table) {
            $table->unsignedTinyInteger('type')->nullable()->index();
            $table->unsignedTinyInteger('manufacture_type')->nullable()->index();
            $table->integer('warehouse_group_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_price_tmps', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('manufacture_type');
            $table->dropColumn('warehouse_group_id');
        });
    }
}
