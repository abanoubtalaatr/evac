<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeletedApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable();
            $table->string('passport_no');
            $table->string('reference_no');
            $table->string('applicant_name');
            $table->dateTime('application_create_date');
            $table->dateTime('deletion_date');
            $table->string('user_name')->comment('who from users that have administration delete the application');
            $table->string('office_name');
            $table->text('delete_reason');
            $table->string('last_app_status');
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
        Schema::dropIfExists('deleted_applications');
    }
}
