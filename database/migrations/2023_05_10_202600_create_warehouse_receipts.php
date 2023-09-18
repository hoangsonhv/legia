<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseReceipts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('delivery_name')->nullable();
            $table->text('note')->nullable();
            $table->string('warehouse_at')->nullable();
            $table->string('address')->nullable();
            $table->integer('created_by');
            $table->decimal('amount_paid', 17)->nullable();
            $table->decimal('amount_owed', 17)->nullable();
            $table->decimal('total', 17)->default(0)->nullable();
            $table->integer('vat')->default(0)->nullable();
            $table->decimal('total_vat', 17)->default(0)->nullable();
            $table->decimal('total_payment', 17)->default(0)->nullable();
            $table->text('document')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouse_receipt_products', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_receipt_id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('unit')->nullable();
            $table->integer('quantity_doc')->nullable();
            $table->integer('quantity_reality')->nullable();
            $table->decimal('unit_price', 17)->nullable();
            $table->decimal('into_money', 17)->nullable();
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
        Schema::dropIfExists('warehouse_receipts');
        Schema::dropIfExists('warehouse_receipt_products');
    }
}
