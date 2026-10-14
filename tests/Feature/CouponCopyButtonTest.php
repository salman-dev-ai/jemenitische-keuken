<?php

use App\Enums\CouponScope;
use App\Enums\DiscountType;
use App\Livewire\MenuWithCart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Customer;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use Livewire\Livewire;

it('renders a safe browser-copy flow for promotion coupon buttons', function () {
    $coupon = Coupon::create([
        'code' => 'WELCOME10',
        'name' => ['en' => 'Welcome discount', 'ar' => 'خصم ترحيبي'],
        'description' => ['en' => 'Welcome offer', 'ar' => 'عرض ترحيبي'],
        'discount_type' => DiscountType::Percentage,
        'discount_value' => 10,
        'applies_to' => CouponScope::All,
        'min_order_total' => 0,
        'max_uses' => 100,
        'max_uses_per_customer' => 1,
        'used_count' => 0,
        'is_active' => true,
        'is_welcome' => false,
    ]);

    $promotion = Promotion::create([
        'coupon_id' => $coupon->id,
        'title' => ['en' => 'Welcome Offer', 'ar' => 'عرض ترحيبي'],
        'subtitle' => ['en' => 'Save 10%', 'ar' => 'وفر 10%'],
        'description' => ['en' => 'Limited time offer', 'ar' => 'عرض لفترة محدودة'],
        'cta_text' => ['en' => 'Use now', 'ar' => 'استخدم الآن'],
        'is_active' => true,
        'is_featured' => false,
        'sort_order' => 1,
    ]);

    $html = view('livewire.offers.partials.card', ['promotion' => $promotion])->render();

    expect($html)
        ->toContain("document.execCommand('copy')")
        ->toContain("open-coupon-modal")
        ->toContain("action: 'copy'");
});

it('claims a valid coupon when applied so it appears in my coupons', function () {
    $customer = Customer::create([
        'name' => 'Test Customer',
        'phone' => '966500000001',
        'email' => 'test@example.com',
        'remember_token' => 'token-123',
    ]);

    $category = MenuCategory::create([
        'name' => ['en' => 'Main', 'ar' => 'رئيسي'],
        'slug' => 'main',
        'description' => ['en' => 'Main dishes', 'ar' => 'أطباق رئيسية'],
        'image_path' => null,
        'sort_order' => 1,
        'is_available' => true,
    ]);

    $menuItem = MenuItem::create([
        'menu_category_id' => $category->id,
        'name' => ['en' => 'Test Dish', 'ar' => 'طبق اختبار'],
        'slug' => 'test-dish',
        'description' => ['en' => 'A test dish', 'ar' => 'طبق تجريبي'],
        'allergens' => ['n/a'],
        'price' => 20.00,
        'image_path' => null,
        'is_available' => true,
        'is_featured' => false,
        'is_spicy' => false,
        'sort_order' => 1,
    ]);

    $coupon = Coupon::create([
        'code' => 'SAVE10',
        'name' => ['en' => 'Save 10', 'ar' => 'وفر 10'],
        'description' => ['en' => '10% off', 'ar' => 'خصم 10%'],
        'discount_type' => DiscountType::Percentage,
        'discount_value' => 10,
        'applies_to' => CouponScope::All,
        'min_order_total' => 0,
        'max_uses' => 100,
        'max_uses_per_customer' => 1,
        'used_count' => 0,
        'is_active' => true,
        'is_welcome' => false,
    ]);

    $component = Livewire::withCookies([
        'remember_customer_token' => $customer->remember_token,
    ])->test(MenuWithCart::class)
        ->set('cart', [
            $menuItem->id => [
                'id' => $menuItem->id,
                'name' => 'Test Dish',
                'price' => 20.00,
                'quantity' => 1,
            ],
        ])
        ->set('couponCode', $coupon->code)
        ->call('applyCoupon', app(\App\Services\CouponService::class));

    expect($component->get('appliedCouponCode'))->toBe('SAVE10')
        ->and(CouponUsage::query()->where('customer_id', $customer->id)->where('coupon_id', $coupon->id)->exists())->toBeTrue();
});
