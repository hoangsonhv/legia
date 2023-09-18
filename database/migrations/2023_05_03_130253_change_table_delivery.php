<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableDelivery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co', function (Blueprint $table) {
            $table->integer('delivery_id')->nullable()->index();
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->integer('core_customer_id')->index();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->dateTime('received_date_expected')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co', function (Blueprint $table) {
            $table->dropColumn('delivery_id');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('core_customer_id');
            $table->dropColumn('recipient_name');
            $table->dropColumn('recipient_phone');
            $table->dropColumn('received_date_expected');
        });
    }
}
