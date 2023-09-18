<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePriceSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_survey', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->tinyInteger('type')->default(1);
            $table->integer('core_customer_id')->index();
            $table->text('product_group');
            $table->text('request_person')->nullable();
            $table->date('date_request')->nullable();
            $table->date('question_date')->nullable();
            $table->date('result_date')->nullable();
            $table->date('deadline')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('price');
            $table->string('vendor_code')->nullable();
            $table->integer('number_date_wait_pay')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('price_survey');
    }
}
