<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل الهجرات.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable(); // بدون قيد unique
            $table->string('phone')->nullable(); // بدون قيد unique
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('remember_token', 100)->nullable()->unique(); // لتخزين توكن العميل في الكوكيز
            $table->timestamp('last_order_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // لدعم Soft Deletes
        });
    }

    /**
     * عكس الهجرات.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};