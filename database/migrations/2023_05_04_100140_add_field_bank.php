<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->bigInteger('opening_balance')->default(0);
        });

        Schema::table('bank_history_transactions', function (Blueprint $table) {
            $table->bigInteger('current_opening_balance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('opening_balance');
        });

        Schema::table('bank_history_transactions', function (Blueprint $table) {
            $table->dropColumn('current_opening_balance');
        });
    }
}
