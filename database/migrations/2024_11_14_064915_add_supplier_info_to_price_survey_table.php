<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierInfoToPriceSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_survey', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->references('id')->on('suppliers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_survey', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
        });
    }
}
