<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldBankIdReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->integer('bank_id')->after('payment_method')->default(0);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('bank_id')->after('used')->default(0);
            $table->tinyInteger('payment_method')->default(0);
        });
        DB::statement("ALTER TABLE `banks` MODIFY `account_balance` BIGINT(20)");

        Schema::table('bank_history_transactions', function (Blueprint $table) {
            $table->integer('payment_id')->default(0);
            $table->integer('receipt_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn('bank_id');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('bank_id');
            $table->dropColumn('payment_method');
        });
    }
}
