<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Responsibility: Stores full details for menu dishes including localized attributes, prices, and status.
     */
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();

            // translatable content
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->json('allergens')->nullable(); // Dietary notes (Lactose, Gluten, Nuts)

            // financial & media
            $table->decimal('price', 8, 2);
            $table->string('image_path')->nullable();

            // flags for UI & control
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_spicy')->default(false);
            
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        // : يحفظ الوجبات أو العناصر بحيث إذا تم حذف عنصر
        //  لا يختفي نهائياً ويمكن استرجاعه.
            $table->softDeletes();

            // هذا السطر يقوم بإنشاء فهرس مركب (Composite Index)
            //  يجمع بين عمودين: menu_category_id و is_available.
            // // Performance Index
            $table->index(['menu_category_id', 'is_available']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
