<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousePhutungdungcusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_phutungdungcus', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('mo_ta')->nullable();
            $table->string('cho_maymoc_thietbi')->nullable();
            $table->float('sl_cai')->nullable();
            $table->string('so_hopdong_hoadon')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->date('date')->nullable();
            $table->float('ton_sl_cai')->nullable();
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
        Schema::dropIfExists('warehouse_phutungdungcus');
    }
}
