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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();

            // Logo Settings
            $table->string('logo_header_path')->nullable();
            $table->string('logo_header_alt_text')->nullable();
            $table->string('logo_footer_path')->nullable();
            $table->string('logo_footer_alt_text')->nullable();
            
            // Footer Video
            $table->string('footer_video_path')->nullable();
            $table->string('footer_video_alt_text')->nullable();
            
            // Favicon
            $table->string('favicon_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
