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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon_path');
            $table->string('icon_alt_text')->nullable();
            $table->string('banner_image_path');
            $table->string('banner_image_alt_text')->nullable();
            $table->text('short_description');
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_home')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
