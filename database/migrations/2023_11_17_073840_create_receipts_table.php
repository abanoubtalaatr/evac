<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->nullable();
            $table->foreignId('application_id');
            $table->string('model');
            $table->string('payment_method');
            $table->string('receipt_no');
            $table->float('dubai_fee')->nullable();
            $table->float('leb_fee')->nullable();
            $table->float('visa_fee')->nullable();
            $table->float('service_fee')->nullable();
            $table->float('vat_rate')->nullable();
            $table->float('vat')->nullable();
            $table->float('total_fee')->nullable();
            $table->float('amount_paid_in_usd')->nullable();
            $table->string('payment_currency');
            $table->boolean('is_printed')->default(0);
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
        Schema::dropIfExists('receipts');
    }
}
