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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('location');
            $table->string('year');
            
            // Media (bisa gambar atau video)
            $table->string('media_path');
            $table->string('media_alt_text')->nullable();
            $table->enum('media_type', ['image', 'video'])->default('image');
            
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
