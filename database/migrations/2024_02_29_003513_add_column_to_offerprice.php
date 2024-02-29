<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToOfferprice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_price_tmps', function (Blueprint $table) {
            $table->integer('vat')->default(10);
            $table->integer('vat_money')->default(0);
        });
        Schema::table('offer_prices', function (Blueprint $table) {
            $table->integer('vat')->default(10);
            $table->integer('vat_money')->default(0);
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
