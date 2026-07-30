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
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id();

            // bisk info
            $table->json('name'); // note: use json type to use in translatable
            $table->string('phone');
            $table->string('email');
            $table->string('whatsapp');

            // address details (netherland location)
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('google_maps_link')->nullable();

            // operations
            $table->json('opening_hours')->nullable();
            $table->boolean('accepts_reservations')->default(true);
            $table->boolean('accepts_online_orders')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_settings');
    }
};
