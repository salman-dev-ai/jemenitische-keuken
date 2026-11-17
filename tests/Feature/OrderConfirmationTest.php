<?php

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

/**
 * استخراج نص الرسالة من رابط wa.me بعد فك ترميزه.
 */
function orderWhatsAppMessageFrom(?string $url): string
{
    expect($url)->not->toBeNull();

    $text = substr($url, strpos($url, 'text=') + 5);

    return urldecode($text);
}

test('يبني رابط واتساب برقم يمني دولي ورسالة بلغة العميل عند إكمال الطلب', function () {
    $order = Order::factory()->create([
        'customer_name' => 'أحمد',
        'customer_phone' => '0771924870',
        'locale' => 'ar',
        'status' => OrderStatus::COMPLETED,
        'total' => 25.50,
        'order_number' => 'ORD-TEST-1',
    ]);

    $menuItem = MenuItem::factory()->create([
        'name' => ['ar' => 'مندي لحم', 'en' => 'Mandi', 'nl' => 'Mandi'],
    ]);

    $order->items()->create([
        'menu_item_id' => $menuItem->id,
        'quantity' => 2,
        'unit_price' => 12.75,
        'total_price' => 25.50,
    ]);

    $url = app(OrderService::class)->customerWhatsAppUrl($order->fresh());

    expect($url)->toStartWith('https://wa.me/967771924870?text=');

    expect(orderWhatsAppMessageFrom($url))
        ->toContain('ORD-TEST-1')
        ->toContain('مندي لحم')
        ->toContain('25.50')
        ->toContain('مكتمل');
});

test('يذكر الإلغاء في الرسالة عندما تكون حالة الطلب ملغاة', function () {
    $order = Order::factory()->create([
        'customer_phone' => '0771924870',
        'locale' => 'ar',
        'status' => OrderStatus::CANCELLED,
        'order_number' => 'ORD-TEST-2',
    ]);

    $message = app(OrderService::class)->buildOrderStatusMessage($order);

    expect($message)
        ->toContain('ORD-TEST-2')
        ->toContain('ملغي');
});

test('يستخدم اللغة الافتراضية للموقع عندما لا تُخزَّن لغة العميل', function () {
    $order = Order::factory()->create([
        'customer_phone' => '0771924870',
        'locale' => null,
        'status' => OrderStatus::COMPLETED,
    ]);

    $message = app(OrderService::class)->buildOrderStatusMessage($order);

    expect($message)->toContain('Bestelnummer:');
});

test('يُرجع null عندما يكون رقم العميل غير صالح', function () {
    $order = Order::factory()->create([
        'customer_phone' => '   ',
        'locale' => 'ar',
        'status' => OrderStatus::COMPLETED,
    ]);

    expect(app(OrderService::class)->customerWhatsAppUrl($order))->toBeNull();
});

test('يظهر زر إرسال تحديث الطلب على واتساب في جدول الطلبات المكتملة', function () {
    Role::findOrCreate('super_admin');

    $admin = User::factory()->create();
    $admin->assignRole('super_admin');

    $order = Order::factory()->create([
        'customer_phone' => '0771924870',
        'locale' => 'ar',
        'status' => OrderStatus::COMPLETED,
    ]);

    Livewire::actingAs($admin)
        ->test(ListOrders::class)
        ->assertOk()
        ->assertTableActionVisible('send_status_whatsapp', $order);
});
