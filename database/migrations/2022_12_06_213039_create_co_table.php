<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->smallInteger('status')->default(1);
            $table->boolean('used')->default(0);
            $table->string('so_bao_gia')->nullable();
            $table->date('ngay_bao_gia')->nullable();
            $table->string('sales')->nullable();
            $table->string('thoi_han_bao_gia')->nullable();
            $table->unsignedDecimal('tong_gia', 17, 0);
            $table->unsignedDecimal('vat', 17, 0);
            $table->smallInteger('dong_goi_va_van_chuyen')->nullable();
            $table->text('noi_giao_hang')->nullable();
            $table->text('thoi_gian_giao_hang')->nullable();
            $table->json('thanh_toan')->nullable();
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
        Schema::dropIfExists('co');
    }
}
