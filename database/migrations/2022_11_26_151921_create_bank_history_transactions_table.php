<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankHistoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_history_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bank_id')->unsigned();
            $table->bigInteger('admin_id')->unsigned();
            $table->enum('type', ['withdraw', 'deposit']);
            $table->unsignedDecimal('transaction_amount', 15, 0);
            $table->unsignedDecimal('current_amount', 15, 0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_history_transactions');
    }
}
