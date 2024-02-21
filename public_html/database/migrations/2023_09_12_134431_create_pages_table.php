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
        Schema::create('pages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title_ar', 500);
            $table->string('title_en', 500);
            $table->string('desc_ar', 500)->nullable();
            $table->string('desc_en', 500)->nullable();
            $table->text('content_ar');
            $table->text('content_en');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->enum('type', ['navbar', 'services', 'benifits', 'how_it_works', 'footer'])->nullable();
            $table->string('picture', 350)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
