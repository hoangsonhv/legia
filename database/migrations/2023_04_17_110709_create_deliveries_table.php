<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('co_id')->unsigned();
            $table->bigInteger('admin_id')->unsigned();
            $table->text('shipping_unit');
            $table->dateTime('delivery_date');
            $table->text('shipping_method');
            $table->unsignedDecimal('shipping_fee', 15, 0)->default(0);
            $table->boolean('status_customer_received')->default(0);
            $table->timestamps();
        });

        // Change column timeline
        if (Schema::hasColumn('receipts', 'enough_money')) {
            Schema::table('receipts', function (Blueprint $table) {
                $table->dropColumn('enough_money');
            });
        }
        if (Schema::hasColumn('receipts', 'start_timeline')) {
            Schema::table('receipts', function (Blueprint $table) {
                $table->dropColumn('start_timeline');
            });
        }

        if (!Schema::hasColumn('co', 'confirm_done')) {
            Schema::table('co', function (Blueprint $table) {
                $table->boolean('confirm_done')->default(0)->after('used');
            });
        }
        if (!Schema::hasColumn('co', 'co_tmp_id')) {
            Schema::table('co', function (Blueprint $table) {
                $table->bigInteger('co_tmp_id')->unsigned()->nullable()->after('code');
            });
        }
        if (!Schema::hasColumn('co', 'enough_money')) {
            Schema::table('co', function (Blueprint $table) {
                $table->dateTime('enough_money')->nullable()->after('used');
            });
        }
        if (!Schema::hasColumn('co', 'start_timeline')) {
            Schema::table('co', function (Blueprint $table) {
                $table->dateTime('start_timeline')->nullable()->after('used');
            });
        }
        if (!Schema::hasColumn('co', 'admin_id')) {
            Schema::table('co', function (Blueprint $table) {
                $table->bigInteger('admin_id')->unsigned()->nullable()->after('code');
            });
        }
        if (!Schema::hasColumn('co', 'contract_document')) {
            Schema::table('co', function (Blueprint $table) {
                $table->text('contract_document')->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('co', 'invoice_document')) {
            Schema::table('co', function (Blueprint $table) {
                $table->text('invoice_document')->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
