<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufactureDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacture_details', function (Blueprint $table) {
            $table->id();
            $table->integer('manufacture_id')->index();
            $table->string('offer_price_id');
            $table->integer('reality_quantity')->nullable();
            $table->tinyInteger('material_type')->nullable();
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
        Schema::dropIfExists('manufacture_details');
    }
}
