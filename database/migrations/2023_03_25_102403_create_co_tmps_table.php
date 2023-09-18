<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoTmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_tmps', function (Blueprint $table) {
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
            $table->text('dong_goi_va_van_chuyen')->nullable();
            $table->text('noi_giao_hang')->nullable();
            $table->text('xuat_xu')->nullable();
            $table->text('thoi_gian_giao_hang')->nullable();
            $table->json('thanh_toan')->nullable();
            $table->timestamps();

            $table->index(['status']);
        });

        Schema::table('co', function (Blueprint $table) {
            $table->text('dong_goi_va_van_chuyen')->change();
            $table->text('xuat_xu')->nullable()->after('noi_giao_hang');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger('co_id')->unsigned()->nullable()->change();
            $table->bigInteger('co_tmp_id')->unsigned()->nullable()->after('co_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_tmps');
    }
}
