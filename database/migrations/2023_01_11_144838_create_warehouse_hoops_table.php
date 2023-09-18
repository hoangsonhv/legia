<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseHoopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_hoops', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('vat_lieu');
            $table->float('size')->nullable();
            $table->float('trong_luong_kg_cuon')->nullable();
            $table->float('m_cuon')->nullable();
            $table->float('sl_cuon')->nullable();
            $table->float('sl_kg')->nullable();
            $table->string('lot_no')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->date('date')->nullable();
            $table->float('ton_sl_cuon')->nullable();
            $table->float('ton_sl_kg')->nullable();
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
        Schema::dropIfExists('warehouse_hoops');
    }
}
