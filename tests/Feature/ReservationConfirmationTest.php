<?php

use App\Enums\ReservationStatus;
use App\Filament\Resources\Reservations\Pages\ListReservations;
use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationService;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

/**
 * استخراج نص الرسالة من رابط wa.me بعد فك ترميزه.
 */
function whatsAppMessageFrom(?string $url): string
{
    expect($url)->not->toBeNull();

    $text = substr($url, strpos($url, 'text=') + 5);

    return urldecode($text);
}

test('يبني رابط واتساب برقم يمني دولي ورسالة بلغة العميل', function () {
    $reservation = Reservation::factory()->create([
        'customer_name' => 'أحمد',
        'customer_phone' => '0771924870',
        'locale' => 'ar',
        'reservation_date' => '2026-12-01',
        'reservation_time' => '19:30',
        'party_size' => 4,
        'special_requests' => null,
    ]);

    $url = app(ReservationService::class)->customerWhatsAppUrl($reservation);

    expect($url)->toStartWith('https://wa.me/967771924870?text=');

    expect(whatsAppMessageFrom($url))
        ->toContain('أحمد')
        ->toContain($reservation->reference_code)
        ->toContain('2026-12-01')
        ->toContain('19:30')
        ->toContain('4');
});

test('يستخدم اللغة الافتراضية للموقع عندما لا تُخزَّن لغة العميل', function () {
    $reservation = Reservation::factory()->create([
        'customer_phone' => '0771924870',
        'locale' => null,
    ]);

    $message = app(ReservationService::class)->buildCustomerConfirmationMessage($reservation);

    expect($message)->toContain('Referentie:');
});

test('يُرجع null عندما يكون رقم العميل غير صالح', function () {
    $reservation = Reservation::factory()->create([
        'customer_phone' => '   ',
        'locale' => 'ar',
    ]);

    expect(app(ReservationService::class)->customerWhatsAppUrl($reservation))->toBeNull();
});

test('يظهر زر إرسال التأكيد على واتساب في جدول الحجوزات المؤكدة', function () {
    Role::findOrCreate('super_admin');

    $admin = User::factory()->create();
    $admin->assignRole('super_admin');

    $reservation = Reservation::factory()->create([
        'customer_phone' => '0771924870',
        'locale' => 'ar',
        'status' => ReservationStatus::CONFIRMED,
    ]);

    Livewire::actingAs($admin)
        ->test(ListReservations::class)
        ->assertOk()
        ->assertTableActionVisible('send_confirmation_whatsapp', $reservation);
});
