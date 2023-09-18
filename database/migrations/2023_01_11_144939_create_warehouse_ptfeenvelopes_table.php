<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousePtfeenvelopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_ptfeenvelopes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('vat_lieu');
            $table->float('do_day')->nullable();
            $table->string('std')->nullable();
            $table->string('size')->nullable();
            $table->float('od')->nullable();
            $table->float('attr_id')->nullable();
            $table->float('sl_cai')->nullable();
            $table->string('lot_no')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->date('date')->nullable();
            $table->float('ton_sl_cai')->nullable();
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
        Schema::dropIfExists('warehouse_ptfeenvelopes');
    }
}
