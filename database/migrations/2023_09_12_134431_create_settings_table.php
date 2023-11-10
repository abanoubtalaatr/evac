<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('intro_video', 350)->nullable();
            $table->string('email', 350);
            $table->string('address', 350);
            $table->string('mobile', 350);
            $table->string('lat', 191)->nullable();
            $table->string('lng', 191)->nullable();
            $table->string('facebook', 350)->nullable();
            $table->string('instagram', 350)->nullable();
            $table->string('twitter', 350)->nullable();
            $table->string('youtube', 350)->nullable();
            $table->text('footer_word_en')->nullable();
            $table->text('mission_ar')->nullable();
            $table->text('vision_ar')->nullable();
            $table->text('mission_en')->nullable();
            $table->text('vision_en')->nullable();
            $table->tinyInteger('pay_online')->default(1);
            $table->tinyInteger('pay_cash')->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
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
};
