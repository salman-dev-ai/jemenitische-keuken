<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->boolean('online_payments_enabled')->default(false);
            $table->boolean('cash_on_delivery_enabled')->default(true);
            $table->text('paypro_api_key')->nullable();        // مشفّر
            $table->text('paypro_webhook_secret')->nullable(); // مشفّر
            $table->string('paypro_webhook_id')->nullable();
            $table->timestamp('paypro_connected_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('restaurant_settings', function (Blueprint $table) {
            $table->dropColumn([
                'online_payments_enabled',
                'cash_on_delivery_enabled',
                'paypro_api_key',
                'paypro_webhook_secret',
                'paypro_webhook_id',
                'paypro_connected_at',
            ]);
        });
    }
};
