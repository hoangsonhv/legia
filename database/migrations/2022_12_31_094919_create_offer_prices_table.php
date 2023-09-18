<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('co_id')->unsigned();
            $table->string('code');
            $table->string('loai_vat_lieu');
            $table->string('do_day')->nullable();
            $table->string('tieu_chuan')->nullable();
            $table->string('kich_co')->nullable();
            $table->string('kich_thuoc')->nullable();
            $table->string('chuan_bich')->nullable();
            $table->string('chuan_gasket')->nullable();
            $table->string('dv_tinh')->nullable();
            $table->integer('so_luong');
            $table->unsignedDecimal('don_gia', 15, 0);
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
        Schema::dropIfExists('offer_prices');
    }
}
