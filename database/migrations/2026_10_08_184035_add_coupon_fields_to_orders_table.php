<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (!Schema::hasColumn('orders', 'coupon_id')) {
                $table->foreignId('coupon_id')
                    ->nullable()
                    ->after('notes')
                    ->constrained('coupons')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code', 50)
                    ->nullable()
                    ->after('coupon_id')
                    ->index();
            }

            if (!Schema::hasColumn('orders', 'discount_type')) {
                $table->string('discount_type', 20)
                    ->nullable()
                    ->after('coupon_code');
            }

            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 8, 2)
                    ->nullable()
                    ->default(0.00)
                    ->after('discount_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (Schema::hasColumn('orders', 'coupon_id')) {
                $table->dropConstrainedForeignId('coupon_id');
            }
            if (Schema::hasColumn('orders', 'coupon_code')) {
                $table->dropIndex(['coupon_code']);
                $table->dropColumn('coupon_code');
            }
            if (Schema::hasColumn('orders', 'discount_type')) {
                $table->dropColumn('discount_type');
            }
            if (Schema::hasColumn('orders', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
        });
    }
};
