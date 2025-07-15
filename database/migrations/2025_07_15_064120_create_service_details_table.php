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
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            
            // Header Section
            $table->string('header_title');
            $table->string('header_image_path');
            $table->string('header_image_alt_text')->nullable();
            
            // Content Section  
            $table->string('content_title');
            $table->longText('content_description');
            $table->string('content_image_path');
            $table->string('content_image_alt_text')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_details');
    }
};
