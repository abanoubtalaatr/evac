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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('car_brand_id')->nullable();
            $table->foreignId('car_module_id')->nullable();
            $table->string('fuel_type');
            $table->integer('manufacturing_year');
            $table->string('plat_number');
            $table->string('plat_char');
            $table->integer('number_of_engine_valves');
            $table->string('color');
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
        Schema::dropIfExists('cars');
    }
};
