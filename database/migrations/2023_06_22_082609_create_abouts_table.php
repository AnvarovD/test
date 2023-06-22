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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->default('about');
            $table->string('banner_title_en')->nullable();
            $table->string('banner_subtitle_en')->nullable();
            $table->text('banner_description_en')->nullable();
            $table->string('banner_title_uz')->nullable();
            $table->string('banner_subtitle_uz')->nullable();
            $table->text('banner_description_uz')->nullable();
            $table->string('banner_title_ru')->nullable();
            $table->string('banner_subtitle_ru')->nullable();
            $table->text('banner_description_ru')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('content_title_en')->nullable();
            $table->string('content_subtitle_en')->nullable();
            $table->text('content_description_en')->nullable();
            $table->string('content_title_uz')->nullable();
            $table->string('content_subtitle_uz')->nullable();
            $table->text('content_description_uz')->nullable();
            $table->string('content_title_ru')->nullable();
            $table->string('content_subtitle_ru')->nullable();
            $table->text('content_description_ru')->nullable();
            $table->string('footer_title_en')->nullable();
            $table->string('footer_subtitle_en')->nullable();
            $table->text('footer_description_en')->nullable();
            $table->string('footer_title_uz')->nullable();
            $table->string('footer_subtitle_uz')->nullable();
            $table->text('footer_description_uz')->nullable();
            $table->string('footer_title_ru')->nullable();
            $table->string('footer_subtitle_ru')->nullable();
            $table->text('footer_description_ru')->nullable();
            $table->string('footer_image')->nullable();
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
        Schema::dropIfExists('abouts');
    }
};
