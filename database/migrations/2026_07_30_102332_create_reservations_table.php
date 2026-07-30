<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 /**
     * Run the migrations.
     * Responsibility: Handles online table reservations placed by customers.
     */
    public function up(): void
    {
   Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique(); // Public reference (e.g., RES-8X29B)
/// NULL تعيين قيمة الحقل إلى
//  تلقائياً إذا تم حذف السجل الأساسي المرتبط به في الجدول الآخر بدلاً من حذف السجل الحالي
            $table->foreignId('table_id')->nullable()->nullOnDelete();

            // Customer Information
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone');

            // Details
            $table->integer('party_size');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->text('special_requests')->nullable();

            // Statuses: pending, confirmed, seated, cancelled, no_show
            $table->string('status')->default('pending');

            $table->timestamps();

            // Index for fast daily lookup
            $table->index(['reservation_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
