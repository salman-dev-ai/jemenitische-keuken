<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Responsibility: علاقة متعدد-لمتعدد بين الأطباق ومعلومات الحساسية،
     * فلا تُخزَّن الأسماء كنص حر داخل جدول الأطباق (بدون تكرار).
     */
    public function up(): void
    {
        Schema::create('allergen_menu_item', function (Blueprint $table) {
            $table->id();

            $table->foreignId('allergen_id')
                ->constrained('allergens')
                ->cascadeOnDelete();

            $table->foreignId('menu_item_id')
                ->constrained('menu_items')
                ->cascadeOnDelete();

            $table->timestamps();

            // يمنع ربط نفس العنصر بالطبق أكثر من مرة
            $table->unique(['allergen_id', 'menu_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergen_menu_item');
    }
};
