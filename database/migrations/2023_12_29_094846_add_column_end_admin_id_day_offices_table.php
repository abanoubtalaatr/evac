<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEndAdminIdDayOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('day_offices', function (Blueprint $table) {
            $table->foreignId('end_admin_id')->after('admin_id')->nullable()->comment('who end the day');
            $table->foreignId('restart_admin_id')->after('admin_id')->nullable()->comment('who restart the day');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
