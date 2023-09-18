<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnCodePriceSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE price_survey CHANGE COLUMN code request_id INTEGER(11)");
        DB::statement("ALTER TABLE price_survey CHANGE COLUMN core_customer_id supplier TEXT ");
        Schema::table('price_survey', function (Blueprint $table) {
                $table->integer('co_id')->nullable()->index();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE price_survey RENAME COLUMN request_id TO code VARCHAR (255)");
        DB::statement("ALTER TABLE price_survey CHANGE COLUMN supplier core_customer_id INTEGER (11)");
        Schema::table('price_survey', function (Blueprint $table) {
            $table->dropColumn('co_id');
        });
    }
}
