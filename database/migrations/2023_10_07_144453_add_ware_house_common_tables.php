<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWareHouseCommonTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('base_warehouses', function (Blueprint $table) { 
            // $table->id();
            $table->string('code')->nullable();
            $table->string('vat_lieu')->nullable();
            $table->float('do_day')->nullable();
            $table->string('hinh_dang')->nullable();
            $table->float('dia_w_w1')->nullable();
            $table->float('l_l1')->nullable();
            $table->float('w2')->nullable();
            $table->float('l2')->nullable();
            $table->integer('sl_tam')->nullable();
            $table->float('sl_m2')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('ghi_chu')->nullable();
            $table->date('date')->nullable();
            $table->integer('ton_sl_tam')->nullable();
            $table->float('ton_sl_m2')->nullable();
            $table->string('size')->nullable();
            $table->float('trong_luong_cuon')->nullable();
            $table->float('m_cuon')->nullable();
            $table->integer('sl_cuon')->nullable();
            $table->float('sl_kg')->nullable();
            $table->integer('ton_sl_cuon')->nullable();
            $table->float('ton_sl_kg')->nullable();
            $table->float('sl_m')->nullable();
            $table->float('d1')->nullable();
            $table->float('d2')->nullable();
            $table->integer('sl_cai')->nullable();
            $table->integer('ton_sl_cai')->nullable();
            $table->string('mo_ta')->nullable();
            $table->string('cho_may_moc_thiet_bi')->nullable();
            $table->string('so_hopdong_hoadon')->nullable();
            $table->string('inner')->nullable();
            $table->string('hoop')->nullable();
            $table->string('filler')->nullable();
            $table->string('outer')->nullable();
            $table->string('thick')->nullable();
            $table->string('tieu_chuan')->nullable();
            $table->string('kich_co')->nullable();
            $table->string('bo_phan')->nullable();
            $table->string('dvt')->nullable();
            $table->integer('sl')->nullable();
            $table->string('std')->nullable();
            $table->integer('od')->nullable();
            $table->integer('id')->nullable();
            $table->float('m_cay')->nullable();
            $table->integer('sl_cay')->nullable();
            $table->integer('ton_sl_cay')->nullable();
            $table->string('muc_ap_luc')->nullable();
            $table->string('kich_thuoc')->nullable();
            $table->string('chuan_mat_bich')->nullable();
            $table->string('chuan_gasket')->nullable();
            $table->integer('sl_ton')->nullable();
            $table->string('model_type')->nullable();
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
        //
        Schema::dropIfExists('base_warehouses');
    }
}
