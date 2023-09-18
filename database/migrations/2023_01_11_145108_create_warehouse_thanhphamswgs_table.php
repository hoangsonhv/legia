<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseThanhphamswgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_thanhphamswgs', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('inner')->nullable();
            $table->string('hoop')->nullable();
            $table->string('filler')->nullable();
            $table->string('outer')->nullable();
            $table->string('thick')->nullable();
            $table->string('tieu_chuan')->nullable();
            $table->string('kich_co')->nullable();
            $table->float('sl_cai')->nullable();
            $table->string('lot_no')->nullable();
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
        Schema::dropIfExists('warehouse_thanhphamswgs');
    }
}
