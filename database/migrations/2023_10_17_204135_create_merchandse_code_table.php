<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchandseCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandise_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index('code');
            $table->text('prefix_code')->nullable(true)->index('prefix_code');
            $table->text('infix_code')->index('infix_code');
            $table->string('summary');
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
        Schema::dropIfExists('merchandse_code');
    }
}
