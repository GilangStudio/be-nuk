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
        Schema::create('contact_page_settings', function (Blueprint $table) {
            $table->id();

            // Banner Image
            $table->string('banner_image_path');
            $table->string('banner_image_alt_text')->nullable();
            
            // Company Information
            $table->string('company_name');
            $table->text('company_address');
            $table->string('whatsapp_customer_relations');
            $table->string('hotline_number');
            $table->string('email');
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            
            // Maps Embed
            $table->text('maps_embed_code');
            
            // Contact Form Image
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
        Schema::dropIfExists('contact_page_settings');
    }
};
