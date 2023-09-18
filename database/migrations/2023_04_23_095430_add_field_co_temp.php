<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCoTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_tmps', function (Blueprint $table) {
            $table->integer('core_customer_id')->nullable();
        });

        Schema::table('co', function (Blueprint $table) {
            $table->integer('core_customer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_tmps', function (Blueprint $table) {
            $table->dropColumn('core_customer_id');
        });

        Schema::table('co', function (Blueprint $table) {
            $table->dropColumn('core_customer_id');
        });
    }
}
