<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldCoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_prices', function (Blueprint $table) {
            $table->unsignedTinyInteger('material_type')->nullable()->default(0);
        });

        Schema::table('manufactures', function (Blueprint $table) {
            $table->unsignedTinyInteger('material_type')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_prices', function (Blueprint $table) {
            $table->dropColumn('material_type');
        });

        Schema::table('manufactures', function (Blueprint $table) {
            $table->dropColumn('material_type');
        });
    }
}
