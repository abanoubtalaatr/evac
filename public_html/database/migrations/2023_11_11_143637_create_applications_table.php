<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_type_id')->nullable()->references('id')->on('visa_types');
            $table->foreignId('visa_provider_id')->nullable()->references('id')->on('visa_providers');
            $table->foreignId('travel_agent_id')->nullable()->references('id')->on('agents');
            $table->string('application_ref')->nullable();
            $table->string('passport_no')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('notes')->nullable();
            $table->float('amount')->nullable();
            $table->string('status')->default('new');
            $table->float('vat')->nullable();
            $table->enum('payment_method',['invoice', 'cash'])->default('invoice');
            $table->boolean('is_print')->default(0);
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
        Schema::dropIfExists('applications');
    }
}
