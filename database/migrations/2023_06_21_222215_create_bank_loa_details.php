<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankLoaDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_loan_details', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_loan_id')->index();
            $table->bigInteger('debit_amount')->nullable();
            $table->bigInteger('profit_amount')->nullable();
            $table->bigInteger('total_amount')->nullable();
            $table->text('note')->nullable();
            $table->integer('admin_id')->index();
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
        Schema::dropIfExists('bank_loan_details');
    }
}
