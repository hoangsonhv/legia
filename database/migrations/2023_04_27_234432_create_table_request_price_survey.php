<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRequestPriceSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_price_survey', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->index();
            $table->integer('price_survey')->index();
            $table->tinyInteger('is_accept');
            $table->tinyInteger('note');
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
        Schema::dropIfExists('request_price_survey');
    }
}
