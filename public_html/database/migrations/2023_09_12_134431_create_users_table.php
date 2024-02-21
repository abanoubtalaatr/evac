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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->string('reset_code')->nullable();
            $table->string('verified_code')->nullable();
            $table->string('password')->nullable();
            $table->string('username')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('avatar', 350)->nullable();
            $table->string('default_language', 50)->nullable();
            $table->tinyInteger('resend_verification_code_num');
            $table->timestamp('verification_code_created_at')->nullable();
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
