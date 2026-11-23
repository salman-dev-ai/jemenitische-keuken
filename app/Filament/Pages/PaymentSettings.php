<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Exceptions\PaymentException;
use App\Models\RestaurantSetting;
use App\Services\Payments\PayProService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class PaymentSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-credit-card';

    protected static ?string $navigationLabel = 'إعدادات الدفع';

    protected static ?string $title = 'إعدادات الدفع الإلكتروني';

    protected static string|UnitEnum|null $navigationGroup = 'إعدادات المطعم';

    protected static ?int $navigationSort = 8;

    protected string $view = 'filament.pages.payment-settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    private function fillForm(): void
    {
        $s = RestaurantSetting::current();

        $this->form->fill([
            'online_payments_enabled' => (bool) $s->online_payments_enabled,
            'cash_on_delivery_enabled' => (bool) $s->cash_on_delivery_enabled,
            'api_key' => null,
            'webhook_url' => route('webhooks.paypro'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $s = RestaurantSetting::current();
        $connected = filled($s->paypro_api_key);
        $masked = $connected ? 'مربوط ••••'.substr((string) $s->paypro_api_key, -4) : 'غير مربوط';

        return $schema
            ->statePath('data')
            ->components([
                Section::make('طرق الدفع المتاحة للزبون')
                    ->schema([
                        Toggle::make('cash_on_delivery_enabled')
                            ->label('الدفع عند الاستلام / التوصيل'),
                        Toggle::make('online_payments_enabled')
                            ->label('الدفع الإلكتروني (PayPro)')
                            ->helperText('لا يعمل إلا بعد ربط مفتاح PayPro صحيح.'),
                    ]),

                Section::make('ربط حساب PayPro')
                    ->description('الحالة: '.$masked)
                    ->schema([
                        TextInput::make('api_key')
                            ->label('مفتاح API')
                            ->password()
                            ->revealable()
                            ->autocomplete(false)
                            ->placeholder($connected ? 'اتركه فارغاً للإبقاء على المفتاح الحالي' : 'pp_...')
                            ->helperText('من لوحة PayPro: Developers ← API keys. يُخزَّن مشفّراً ولا يُعرض بعد الحفظ.'),
                        TextInput::make('webhook_url')
                            ->label('رابط Webhook (يُنشأ تلقائياً عند الحفظ)')
                            ->readOnly()
                            ->dehydrated(false)
                            ->copyable(),
                    ]),
            ]);
    }

    public function save(PayProService $payments): void
    {
        $state = $this->form->getState();
        $enableOnline = (bool) ($state['online_payments_enabled'] ?? false);
        $enableCash = (bool) ($state['cash_on_delivery_enabled'] ?? false);
        $newKey = trim((string) ($state['api_key'] ?? ''));

        if (! $enableOnline && ! $enableCash) {
            $this->fail('يجب تفعيل طريقة دفع واحدة على الأقل.');

            return;
        }

        try {
            if ($newKey !== '') {
                $payments->connect($newKey);
            }
        } catch (PaymentException $e) {
            $this->fail($e->getMessage());

            return;
        }

        $settings = RestaurantSetting::query()->orderBy('id')->firstOrFail();

        if ($enableOnline && blank($settings->paypro_api_key)) {
            $this->fail('أدخل مفتاح PayPro أولاً لتفعيل الدفع الإلكتروني.');

            return;
        }

        $settings->update([
            'online_payments_enabled' => $enableOnline,
            'cash_on_delivery_enabled' => $enableCash,
        ]);

        $this->fillForm();

        Notification::make()
            ->title($newKey !== '' ? 'تم ربط حساب PayPro وحفظ الإعدادات' : 'تم حفظ الإعدادات')
            ->success()
            ->send();
    }

    private function fail(string $message): void
    {
        Notification::make()->title($message)->danger()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('disconnect')
                ->label('فصل حساب PayPro')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => filled(RestaurantSetting::current()->paypro_api_key))
                ->action(function (): void {
                    app(PayProService::class)->disconnect();
                    $this->fillForm();
                    Notification::make()->title('تم فصل الحساب')->success()->send();
                }),
        ];
    }
}
