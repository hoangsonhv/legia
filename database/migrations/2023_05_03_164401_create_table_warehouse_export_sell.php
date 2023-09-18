<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableWarehouseExportSell extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_export_sell', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('core_customer_id')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_address')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->string('buyer_tax_code')->nullable();
            $table->text('note')->nullable();
            $table->integer('created_by');
            $table->tinyInteger('currency');
            $table->decimal('amount_paid', 17)->nullable();
            $table->decimal('amount_owed', 17)->nullable();
            $table->decimal('total', 17)->default(0)->nullable();
            $table->integer('vat')->default(0)->nullable();
            $table->decimal('total_vat', 17)->default(0)->nullable();
            $table->decimal('total_payment', 17)->default(0)->nullable();
            $table->text('document')->nullable();
            $table->tinyInteger('confirm_enough')->default(0)->nullable();
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
        Schema::dropIfExists('warehouse_export_sell');
    }
}
