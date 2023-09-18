<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('co_id')->unsigned()->nullable();
            $table->string('co_code')->nullable();
            $table->bigInteger('request_id')->unsigned()->nullable();
            $table->string('category');
            $table->string('code')->unique();
            $table->text('note')->nullable();
            $table->bigInteger('admin_id')->unsigned();
            $table->string('name_receiver');
            $table->text('accompanying_document')->nullable();
            $table->unsignedDecimal('money_total', 15, 0);
            $table->smallInteger('status')->default(1);
            $table->boolean('used')->default(0);
            $table->timestamps();

            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
