<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('about_page_settings', function (Blueprint $table) {
            $table->id();

            // Banner Image
            $table->string('banner_image_path');
            $table->string('banner_image_alt_text')->nullable();
            
            // About Section
            $table->string('about_image_path');
            $table->string('about_image_alt_text')->nullable();
            $table->string('about_title');
            $table->longText('about_description');
            
            // Description Section
            $table->string('description_title');
            $table->longText('description_content');
            $table->string('description_image_path');
            $table->string('description_image_alt_text')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_page_settings');
    }
};
