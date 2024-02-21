<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlackListPassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('black_list_passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->nullable();
            $table->string('passport_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_expiry');
            $table->text('black_reason')->nullable();
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
        Schema::dropIfExists('black_list_passports');
    }
}
