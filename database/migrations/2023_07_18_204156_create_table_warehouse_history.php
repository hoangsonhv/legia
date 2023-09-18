<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWarehouseHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('object_id')->index();
            $table->unsignedTinyInteger('type')->default(0)->nullable();
            $table->integer('remaining')->default(0)->nullable();
            $table->integer('quantity')->default(0)->nullable();
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
        Schema::dropIfExists('warehouse_histories');
    }
}
