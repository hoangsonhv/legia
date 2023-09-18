<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColAdminIdBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_customer', function (Blueprint $table) {
            $table->integer('admin_id')->nullable();
        });

        Schema::table('banks', function (Blueprint $table) {
            $table->integer('admin_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_customer', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });

        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });
    }
}
