<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestStepHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_step_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->index();
            $table->string('step');
            $table->unsignedTinyInteger('status')->nullable()->default(0);
            $table->integer('object_id')->nullable();
            $table->string('object_type')->nullable();
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
        Schema::dropIfExists('request_step_history');
    }
}
