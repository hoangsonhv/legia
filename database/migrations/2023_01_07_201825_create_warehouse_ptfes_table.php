<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousePtfesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_ptfes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('vat_lieu');
            $table->float('do_day')->nullable();
            $table->string('hinh_dang')->nullable();
            $table->float('dia_w_w1', 12, 2)->unsigned()->nullable();
            $table->float('l_l1', 12, 2)->unsigned()->nullable();
            $table->float('w2', 12, 2)->unsigned()->nullable();
            $table->float('l2', 12, 2)->unsigned()->nullable();
            $table->float('sl_tam')->nullable();
            $table->float('sl_m2')->nullable();
            $table->string('lot_no')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->date('date')->nullable();
            $table->float('ton_sl_tam')->nullable();
            $table->float('ton_sl_m2')->nullable();
            $table->timestamps();

            $table->index(['code', 'vat_lieu']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_ptfes');
    }
}
