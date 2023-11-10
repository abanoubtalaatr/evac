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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('car_id')->nullable();
            $table->foreignId('service_id');
            $table->foreignId('oil_id')->nullable();
            $table->foreignId('oil_brand_id')->nullable();
            $table->integer('number_of_kilo');
            $table->enum('status', ['approved', 'not_approved', 'finished'])->default('not_approved');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('address');
            $table->string('payment_type');
            $table->timestamp('appointment');
            $table->float('total')->default(0);
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
        Schema::dropIfExists('orders');
    }
};
