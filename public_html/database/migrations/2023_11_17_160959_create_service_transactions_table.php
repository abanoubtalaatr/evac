<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id');
            $table->foreignId('agent_id')->nullable();
            $table->string('service_ref');
            $table->string('passport_no')->nullable();
            $table->string('name');
            $table->string('surname');
            $table->text('notes')->nullable();
            $table->float('amount')->nullable();
            $table->float('vat');
            $table->string('payment_method')->default('invoice');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('service_transactions');
    }
}
