<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co', function (Blueprint $table) {
            $table->string('note')->nullable();
        });

        Schema::table('co_tmps', function (Blueprint $table) {
            $table->string('note')->nullable();
            $table->integer('co_not_approved_id')->nullable();
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
            $table->dropColumn('note');
        });
    }
}
