<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepColPaymentReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->tinyInteger('step_id')->nullable()->index();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->tinyInteger('step_id')->nullable()->index();
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
            $table->dropColumn('step_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('step_id');
        });
    }
}
