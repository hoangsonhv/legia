<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('formable_type'); // Loại mô hình phiếu (model class)
            $table->unsignedBigInteger('formable_id'); // ID của phiếu tương ứng
            $table->enum('action', ['created', 'approved', 'updated', 'canceled']); // Loại hành động
            $table->unsignedBigInteger('performed_by'); // ID của user thực hiện
            $table->timestamp('performed_at')->useCurrent();
            $table->integer('previous_status')->nullable();
            $table->integer('new_status');
            $table->text('change_details')->nullable(); // Chi tiết thay đổi
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
        Schema::dropIfExists('change_histories');
    }
}
