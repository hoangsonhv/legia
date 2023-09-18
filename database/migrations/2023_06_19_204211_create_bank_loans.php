<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_loans', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id')->index();
            $table->string('code')->nullable();
            $table->text('lead')->nullable();
            $table->text('content')->nullable();
            $table->date('date')->nullable();
            $table->date('date_due')->nullable();
            $table->unsignedTinyInteger('date_pay')->nullable();
            $table->bigInteger('amount_money')->nullable()->default(0);
            $table->float('profit_amount')->nullable()->default(0);
            $table->bigInteger('outstanding_balance')->nullable()->default(0);
            $table->text('note')->nullable();
            $table->integer('admin_id')->default(0);
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
        Schema::dropIfExists('bank_loans');
    }
}
