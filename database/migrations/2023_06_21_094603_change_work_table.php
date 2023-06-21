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
        Schema::table('works', function (Blueprint $table) {
            $table->string('work_title_uz')->nullable();
            $table->string('work_title_en')->nullable();
            $table->string('work_title_ru')->nullable();
            $table->string('work_sub_title_uz')->nullable();
            $table->string('work_sub_title_en')->nullable();
            $table->string('work_sub_title_ru')->nullable();
            $table->string('file')->nullable();
            $table->boolean('is_video')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            //
        });
    }
};
