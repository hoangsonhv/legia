<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('co_id')->unsigned();
            $table->string('code');
            $table->string('ten');
            $table->text('dia_chi')->nullable();
            $table->string('mst')->nullable();
            $table->string('nguoi_nhan')->nullable();
            $table->string('dien_thoai')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            $table->index(['code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
