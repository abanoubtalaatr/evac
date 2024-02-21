<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable();
            $table->integer('office_id')->nullable()->default(1);
            $table->date('day_start')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('restart_at')->nullable();
            $table->enum('day_status', [0, 1, 2])->default(1)->comment('1 start, 0 end, 2 restart');
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
        Schema::dropIfExists('day_offices');
    }
}
