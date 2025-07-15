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
        Schema::create('career_page_settings', function (Blueprint $table) {
            $table->id();
            
            // Banner Image
            $table->string('banner_image_path');
            $table->string('banner_image_alt_text')->nullable();
            
            // Career Section
            $table->string('career_title');
            $table->text('career_description');
            $table->string('career_image_path');
            $table->string('career_image_alt_text')->nullable();
            
            // Form Section Image
            $table->string('form_image_path');
            $table->string('form_image_alt_text')->nullable();
            
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
        Schema::dropIfExists('career_page_settings');
    }
};
