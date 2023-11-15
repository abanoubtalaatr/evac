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
            $table->string('office_id')->nullable();
            $table->string('office_name')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('mobile')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('no_of_days_to_check_visa')->nullable();
            $table->string('vat_rate')->nullable();
            $table->string('address')->nullable();
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
