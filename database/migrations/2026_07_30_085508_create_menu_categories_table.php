<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Responsibility: Categorizes food items for menu display and filtering.
     */
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();

            // Translatable Fields
            $table->json('name'); // ['en' => 'Main Dishes', 'ar' => 'الأطباق الرئيسية', 'nl' => 'Hoofdgerechten']
            $table->string('slug')->unique();
            $table->json('description')->nullable();

            // UI & Display Control
            $table->string('image_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_categories');
    }
};
