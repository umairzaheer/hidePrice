<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->tinyInteger('enable_app');
            $table->tinyInteger('customize_change_btn');
            // $table->tinyInteger('text_size')->default('15');
            $table->string('text_color')->default('#FFFFFF');
            $table->string('text_background_color')->default('#000000');
            $table->string('change_btn_text');
            $table->string('update_btn_text');
            $table->string('cancel_btn_text');
            $table->tinyInteger('enable_merchant_msg');
            $table->tinyInteger('enable_customer_msg');

            $table->foreign('user_id')
            ->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('settings');
    }
}
