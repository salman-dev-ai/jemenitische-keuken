<?php

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantSetting;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component
{
    public bool $isOpen = false;

    public string $locale;

    public string $currentState = 'greeting';

    public int $currentStep = 0;

    public string $input = '';

    public ?string $editingField = null;

    public array $messages = [];

    public int $fallbackCount = 0;

    public bool $isTyping = false;

    public int $reservationStep = 0;

    public array $reservationData = [
        'party_size' => null,
        'reservation_date' => null,
        'reservation_time' => null,
        'customer_name' => null,
        'customer_phone' => null,
        'customer_email' => null,
        'special_requests' => null,
    ];

    public ?int $createdReservationId = null;

    public ?string $createdReferenceCode = null;

    public array $preorderCart = [];

    public ?string $preorderCategory = null;

    public bool $showFeaturedOnly = false;

    public function mount(): void
    {
        $this->locale = app()->getLocale();
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'greeting',
            'text' => __('messages.chatbot.greeting.message'),
        ];
    }

    public function switchLocale(string $newLocale): void
    {
        if (! in_array($newLocale, ['ar', 'nl', 'en'])) {
            return;
        }
        $this->locale = $newLocale;
        Session::put('locale', $newLocale);
        app()->setLocale($newLocale);
    }

    public function toggleChat(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function closeChat(): void
    {
        $this->isOpen = false;
    }

    public function newConversation(): void
    {
        $this->currentState = 'greeting';
        $this->currentStep = 0;
        $this->input = '';
        $this->editingField = null;
        $this->fallbackCount = 0;
        $this->reservationStep = 0;
        $this->reservationData = [
            'party_size' => null,
            'reservation_date' => null,
            'reservation_time' => null,
            'customer_name' => null,
            'customer_phone' => null,
            'customer_email' => null,
            'special_requests' => null,
        ];
        $this->createdReservationId = null;
        $this->createdReferenceCode = null;
        $this->preorderCart = [];
        $this->preorderCategory = null;
        $this->showFeaturedOnly = false;
        $this->messages = [
            [
                'role' => 'bot',
                'type' => 'greeting',
                'text' => __('messages.chatbot.greeting.message'),
            ],
        ];
    }

    public function startReservation(): void
    {
        $this->currentState = 'reservation_flow';
        $this->reservationStep = 1;
        $this->fallbackCount = 0;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'info',
            'text' => __('messages.chatbot.reservation.start_intro'),
        ];
        $this->askPartySize();
    }

    protected function askPartySize(): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'input',
            'input_type' => 'party_size',
            'text' => __('messages.chatbot.reservation.step_party_size'),
        ];
    }

    public function selectPartySize(string $key): void
    {
        $ranges = [
            'solo' => 2,
            'small' => 4,
            'medium' => 8,
            'large' => 15,
        ];
        $count = $ranges[$key] ?? 2;
        $this->processPartySize($count);
    }

    public function selectDatePreset(string $key): void
    {
        $date = match ($key) {
            'today' => Carbon::now()->toDateString(),
            'tomorrow' => Carbon::now()->addDay()->toDateString(),
            'day_after' => Carbon::now()->addDays(2)->toDateString(),
            default => Carbon::now()->toDateString(),
        };
        $this->processDate($date);
    }

    protected function processPartySize(int|string $count): void
    {
        $count = (int) $count;
        if ($count < 1) {
            $count = 1;
        }
        if ($count > 20) {
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'warning',
                'text' => __('messages.chatbot.reservation.party_size_too_large'),
            ];
            $this->askPartySize();

            return;
        }
        $this->reservationData['party_size'] = $count;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'success',
            'text' => __('messages.chatbot.reservation.party_size_confirmed', ['count' => $count]),
        ];
        $this->reservationStep = 2;
        $this->askDate();
    }

    protected function askDate(): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'input',
            'input_type' => 'date',
            'text' => __('messages.chatbot.reservation.step_date'),
            'min_date' => Carbon::now()->toDateString(),
        ];
    }

    public function updatedInputDate($value): void
    {
        if ($this->currentState === 'reservation_flow' && $this->reservationStep === 2 && $value) {
            $this->processDate($value);
        }
    }

    protected function processDate(string $dateStr): void
    {
        try {
            $date = Carbon::parse($dateStr);
        } catch (Throwable $e) {
            $date = Carbon::now();
        }

        if ($date->isPast() && ! $date->isToday()) {
            $date = Carbon::now();
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'warning',
                'text' => __('messages.chatbot.reservation.date_past'),
            ];
        }

        $this->reservationData['reservation_date'] = $date->toDateString();
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'success',
            'text' => __('messages.chatbot.reservation.date_confirmed', ['date' => $date->locale($this->locale)->isoFormat('LL')]),
        ];
        $this->reservationStep = 3;
        $this->askTime();
    }

    protected function askTime(): void
    {
        $settings = $this->settings;
        $hours = is_array($settings?->opening_hours) ? $settings->opening_hours : [];
        $start = $hours['default']['open'] ?? '12:00';
        $end = $hours['default']['close'] ?? '22:00';

        $this->messages[] = [
            'role' => 'bot',
            'type' => 'input',
            'input_type' => 'time',
            'text' => __('messages.chatbot.reservation.step_time', ['start' => $start, 'end' => $end]),
            'time_slots' => $this->generateTimeSlots($start, $end),
            'min_time' => Carbon::now()->addMinutes(60)->format('H:i'),
        ];
    }

    protected function generateTimeSlots(string $start, string $end): array
    {
        $slots = [];
        $current = Carbon::createFromFormat('H:i', $start);
        $endTime = Carbon::createFromFormat('H:i', $end);
        while ($current->lte($endTime)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes(30);
        }

        return $slots;
    }

    public function selectTimeSlot(string $time): void
    {
        $this->processTime($time);
    }

    public function updatedInputTime($value): void
    {
        if ($this->currentState === 'reservation_flow' && $this->reservationStep === 3 && $value) {
            $this->processTime($value);
        }
    }

    protected function processTime(string $time): void
    {
        try {
            $timeObj = Carbon::createFromFormat('H:i', substr($time, 0, 5));
        } catch (Throwable $e) {
            $timeObj = Carbon::now()->addMinutes(90);
        }
        $formattedTime = $timeObj->format('H:i');

        $date = Carbon::parse($this->reservationData['reservation_date']);
        if ($date->isToday()) {
            $minTime = Carbon::now()->addMinutes(60);
            if ($timeObj->lt($minTime)) {
                $formattedTime = $minTime->format('H:i');
                $this->messages[] = [
                    'role' => 'bot',
                    'type' => 'warning',
                    'text' => __('messages.chatbot.reservation.time_too_early', [
                        'time' => '60 min',
                        'available' => $formattedTime,
                    ]),
                ];
            }
        }

        $this->reservationData['reservation_time'] = $formattedTime;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'success',
            'text' => __('messages.chatbot.reservation.time_confirmed', ['time' => $formattedTime]),
        ];
        $this->reservationStep = 4;
        $this->askName();
    }

    protected function askName(): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'input',
            'input_type' => 'text',
            'field' => 'customer_name',
            'text' => __('messages.chatbot.reservation.step_name'),
        ];
    }

    protected function processName(string $name): void
    {
        $name = trim($name);
        if (mb_strlen($name) < 3) {
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'error',
                'text' => __('messages.chatbot.reservation.name_invalid'),
            ];
            $this->askName();

            return;
        }
        $this->reservationData['customer_name'] = $name;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'success',
            'text' => __('messages.chatbot.reservation.name_confirmed', ['name' => $name]),
        ];
        $this->reservationStep = 5;
        $this->askPhone();
    }

    protected function askPhone(): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'input',
            'input_type' => 'tel',
            'field' => 'customer_phone',
            'text' => __('messages.chatbot.reservation.step_phone'),
        ];
    }

    protected function processPhone(string $phone): void
    {
        $clean = preg_replace('/[^0-9+]/', '', $phone);
        if (strlen($clean) < 8) {
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'error',
                'text' => __('messages.chatbot.reservation.phone_invalid'),
            ];
            $this->askPhone();

            return;
        }
        $this->reservationData['customer_phone'] = $clean;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'success',
            'text' => __('messages.chatbot.reservation.phone_confirmed'),
        ];
        $this->reservationStep = 6;
        $this->askEmail();
    }

    protected function askEmail(): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'input',
            'input_type' => 'email',
            'field' => 'customer_email',
            'text' => __('messages.chatbot.reservation.step_email'),
            'optional' => true,
        ];
    }

    protected function processEmail(string $email): void
    {
        $email = trim($email);
        $lower = mb_strtolower($email);
        if (in_array($lower, ['لا', 'nee', 'no', 'n', 'ل', 'غير', 'skip', ''])) {
            $this->reservationData['customer_email'] = null;
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'info',
                'text' => __('messages.chatbot.reservation.email_skipped'),
            ];
        } else {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->messages[] = [
                    'role' => 'bot',
                    'type' => 'error',
                    'text' => __('messages.chatbot.reservation.email_invalid'),
                ];
                $this->askEmail();

                return;
            }
            $this->reservationData['customer_email'] = $email;
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'success',
                'text' => __('messages.chatbot.reservation.email_confirmed', ['email' => $email]),
            ];
        }
        $this->reservationStep = 7;
        $this->askNotes();
    }

    protected function askNotes(): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'input',
            'input_type' => 'textarea',
            'field' => 'special_requests',
            'text' => __('messages.chatbot.reservation.step_notes'),
            'optional' => true,
        ];
    }

    public function skipNotes(): void
    {
        $this->reservationData['special_requests'] = null;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'info',
            'text' => __('messages.chatbot.reservation.notes_skipped'),
        ];
        $this->showSummary();
    }

    protected function processNotes(string $notes): void
    {
        $notes = trim($notes);
        if (empty($notes)) {
            $this->reservationData['special_requests'] = null;
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'info',
                'text' => __('messages.chatbot.reservation.notes_skipped'),
            ];
        } else {
            $this->reservationData['special_requests'] = mb_substr($notes, 0, 500);
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'success',
                'text' => __('messages.chatbot.reservation.notes_confirmed', ['notes' => $this->reservationData['special_requests']]),
            ];
        }
        $this->showSummary();
    }

    protected function showSummary(): void
    {
        $this->currentState = 'summary';
        $this->reservationStep = 0;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'summary',
            'text' => __('messages.chatbot.summary.title'),
        ];
    }

    public function editField(string $field): void
    {
        $this->editingField = $field;
        $map = [
            'party_size' => 1,
            'reservation_date' => 2,
            'reservation_time' => 3,
            'customer_name' => 4,
            'customer_phone' => 5,
            'customer_email' => 6,
            'special_requests' => 7,
        ];
        $this->reservationStep = $map[$field] ?? 0;

        $this->messages[] = [
            'role' => 'bot',
            'type' => 'info',
            'text' => __('messages.chatbot.summary.editing_prompt', ['field' => __("messages.chatbot.summary.fields.{$field}")]),
        ];

        match ($field) {
            'party_size' => $this->askPartySize(),
            'reservation_date' => $this->askDate(),
            'reservation_time' => $this->askTime(),
            'customer_name' => $this->askName(),
            'customer_phone' => $this->askPhone(),
            'customer_email' => $this->askEmail(),
            'special_requests' => $this->askNotes(),
            default => null,
        };
    }

    public function confirmReservation(): void
    {
        $key = 'chatbot-reservation-'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'error',
                'text' => __('messages.chatbot.errors.rate_limit'),
            ];

            return;
        }
        RateLimiter::hit($key, 60);

        try {
            $service = app(ReservationService::class);
            $data = [
                'party_size' => $this->reservationData['party_size'],
                'reservation_date' => $this->reservationData['reservation_date'],
                'reservation_time' => $this->reservationData['reservation_time'],
                'customer_name' => $this->reservationData['customer_name'],
                'customer_phone' => $this->reservationData['customer_phone'],
                'customer_email' => $this->reservationData['customer_email'],
                'special_requests' => $this->reservationData['special_requests'],
            ];
            $reservation = $service->createReservation($data);
            $this->createdReservationId = $reservation->id;
            $this->createdReferenceCode = $reservation->reference_code;
            $this->currentState = 'completed';
            $this->editingField = null;

            $this->messages[] = [
                'role' => 'bot',
                'type' => 'success_block',
                'text' => __('messages.chatbot.success.title'),
                'reference_code' => $reservation->reference_code,
            ];

            $this->messages[] = [
                'role' => 'bot',
                'type' => 'preorder_prompt',
                'text' => __('messages.chatbot.success.next_question'),
            ];
        } catch (Throwable $e) {
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'error',
                'text' => __('messages.chatbot.errors.reservation_failed').' — '.$e->getMessage(),
            ];
        }
    }

    public function skipPreorder(): void
    {
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'info',
            'text' => __('messages.chatbot.success.final_message'),
        ];
        $this->currentState = 'idle';
    }

    public function startPreorder(): void
    {
        $this->currentState = 'preorder_flow';
        $this->preorderCategory = null;
        $this->showFeaturedOnly = true;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'preorder_intro',
            'text' => __('messages.chatbot.preorder.intro'),
        ];
    }

    #[Computed]
    public function categories()
    {
        return MenuCategory::active()
            ->orderBy('sort_order')
            ->limit(8)
            ->get(['id', 'name_ar', 'name_nl', 'name_en']);
    }

    #[Computed]
    public function settings()
    {
        return RestaurantSetting::first();
    }

    public function setPreorderCategory(?int $id): void
    {
        $this->preorderCategory = $id;
    }

    public function toggleFeatured(): void
    {
        $this->showFeaturedOnly = ! $this->showFeaturedOnly;
    }

    #[Computed]
    public function preorderMenuItems()
    {
        return MenuItem::query()
            ->available()
            ->when($this->showFeaturedOnly, fn ($q) => $q->featured())
            ->when($this->preorderCategory, fn ($q) => $q->where('menu_category_id', $this->preorderCategory))
            ->orderBy('sort_order')
            ->limit(18)
            ->get(['id', 'name_ar', 'name_nl', 'name_en', 'description_ar', 'description_nl', 'description_en', 'price', 'image', 'is_featured']);
    }

    public function addToPreorder(int $menuItemId): void
    {
        $item = MenuItem::find($menuItemId);
        if (! $item) {
            return;
        }
        $locale = $this->locale;
        $nameField = "name_{$locale}";
        $name = $item->$nameField ?? ($item->name_en ?? 'Item');
        $price = (float) $item->price;

        if (isset($this->preorderCart[$menuItemId])) {
            $this->preorderCart[$menuItemId]['quantity'] += 1;
        } else {
            $this->preorderCart[$menuItemId] = [
                'menu_item_id' => $menuItemId,
                'name' => $name,
                'unit_price' => $price,
                'quantity' => 1,
                'image' => $item->image,
            ];
        }
    }

    public function removeFromPreorder(int $menuItemId): void
    {
        if (! isset($this->preorderCart[$menuItemId])) {
            return;
        }
        if ($this->preorderCart[$menuItemId]['quantity'] > 1) {
            $this->preorderCart[$menuItemId]['quantity'] -= 1;
        } else {
            unset($this->preorderCart[$menuItemId]);
        }
    }

    #[Computed]
    public function preorderSubtotal(): float
    {
        $total = 0;
        foreach ($this->preorderCart as $item) {
            $total += $item['unit_price'] * $item['quantity'];
        }

        return round($total, 2);
    }

    #[Computed]
    public function preorderTax(): float
    {
        return round($this->preorderSubtotal * 0.21, 2);
    }

    #[Computed]
    public function preorderTotal(): float
    {
        return round($this->preorderSubtotal + $this->preorderTax, 2);
    }

    public function confirmPreorder(): void
    {
        if (empty($this->preorderCart) || ! $this->createdReservationId) {
            $this->skipPreorder();

            return;
        }

        try {
            DB::beginTransaction();

            $subtotal = $this->preorderSubtotal;
            $tax = $this->preorderTax;
            $total = $this->preorderTotal;

            $order = Order::create([
                'reservation_id' => $this->createdReservationId,
                'order_number' => 'PRE-'.$this->createdReferenceCode.'-'.Str::random(3),
                'customer_name' => $this->reservationData['customer_name'],
                'customer_phone' => $this->reservationData['customer_phone'],
                'customer_email' => $this->reservationData['customer_email'],
                'type' => OrderType::PREORDER,
                'status' => OrderStatus::PENDING,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_status' => 'unpaid',
                'notes' => 'Pre-order via chatbot for reservation '.$this->createdReferenceCode,
            ]);

            foreach ($this->preorderCart as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem['menu_item_id'],
                    'quantity' => $cartItem['quantity'],
                    'unit_price' => $cartItem['unit_price'],
                    'subtotal' => round($cartItem['unit_price'] * $cartItem['quantity'], 2),
                    'name_snapshot' => $cartItem['name'],
                ]);
            }

            DB::commit();

            $this->messages[] = [
                'role' => 'bot',
                'type' => 'success',
                'text' => __('messages.chatbot.preorder.success', ['total' => '€'.number_format($total, 2)]),
            ];

            $this->currentState = 'idle';
            $this->preorderCart = [];

            $this->dispatch('notify', title: __('messages.notifications.preorder_title'), message: __('messages.notifications.preorder_message'));
        } catch (Throwable $e) {
            DB::rollBack();
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'error',
                'text' => $e->getMessage(),
            ];
        }
    }

    public function showMenu(): void
    {
        $this->fallbackCount = 0;
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'menu_info',
            'text' => __('messages.chatbot.keyword_responses.menu_intro'),
        ];
    }

    public function showHours(): void
    {
        $this->fallbackCount = 0;
        $settings = $this->settings;
        $hours = is_array($settings?->opening_hours) ? $settings->opening_hours : [];
        $hoursStr = '';
        foreach ($hours as $day => $h) {
            if (is_array($h)) {
                $hoursStr .= __('messages.days.'.strtolower($day)).': '.($h['open'] ?? '-').' - '.($h['close'] ?? '-')."\n";
            }
        }
        if (! $hoursStr) {
            $hoursStr = '12:00 - 22:00';
        }
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'info',
            'text' => __('messages.chatbot.keyword_responses.hours', ['hours' => trim($hoursStr)]),
        ];
    }

    public function showLocation(): void
    {
        $this->fallbackCount = 0;
        $settings = $this->settings;
        $address = trim((string) ($settings?->localized_address ?? 'Damrak, Amsterdam, Nederland'));
        $mapsLink = trim((string) ($settings?->google_maps_link ?? 'https://maps.google.com'));
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'info_with_action',
            'text' => __('messages.chatbot.keyword_responses.location', ['address' => $address]),
            'action_label' => __('messages.chatbot.keyword_responses.view_map'),
            'action_href' => $mapsLink,
        ];
    }

    public function showContact(): void
    {
        $this->fallbackCount = 0;
        $settings = $this->settings;
        $wa = trim((string) ($settings?->whatsapp ?? '+31000000000'));
        $email = trim((string) ($settings?->email ?? 'info@jemenitischekeuken.nl'));
        $waHref = str_starts_with($wa, 'http') ? $wa : 'https://wa.me/'.preg_replace('/[^0-9]/', '', $wa);
        $this->messages[] = [
            'role' => 'bot',
            'type' => 'info',
            'text' => __('messages.chatbot.keyword_responses.contact', ['whatsapp' => $wa, 'email' => $email]),
        ];
    }

    public function handleQuickAction(string $action): void
    {
        $this->fallbackCount = 0;
        match ($action) {
            'reserve' => $this->startReservation(),
            'menu' => $this->showMenu(),
            'hours' => $this->showHours(),
            'location' => $this->showLocation(),
            'contact' => $this->showContact(),
            default => $this->handleFallback(),
        };
    }

    protected function handleFallback(): void
    {
        $this->fallbackCount++;
        if ($this->fallbackCount >= 3) {
            $settings = $this->settings;
            $wa = trim((string) ($settings?->whatsapp ?? '+31000000000'));
            $waHref = str_starts_with($wa, 'http') ? $wa : 'https://wa.me/'.preg_replace('/[^0-9]/', '', $wa);
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'fallback_stuck',
                'text' => __('messages.chatbot.fallback.stuck_title'),
                'subtitle' => __('messages.chatbot.fallback.stuck_message'),
                'whatsapp_href' => $waHref,
            ];
        } else {
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'fallback',
                'text' => __('messages.chatbot.fallback.message'),
            ];
        }
    }

    protected function matchKeywords(string $input): ?string
    {
        $input = mb_strtolower(trim($input));
        if ($input === '') {
            return null;
        }
        $locale = $this->locale;

        $map = [
            'reserve' => [
                'ar' => ['حجز', 'احجز', 'حجز طاولة', 'طاولة', 'حضور', 'كيف احجز', 'احجز لي', 'موعد', 'تأجيل'],
                'nl' => ['reserveren', 'boeken', 'tafel', 'reservering', 'afspraak', 'plannen'],
                'en' => ['reserve', 'book', 'table', 'reservation', 'booking', 'appointment'],
            ],
            'menu' => [
                'ar' => ['منيو', 'قائمة', 'أطباق', 'ماذا عندكم', 'الأسعار', 'اكلة', 'اكلات', 'طبق', 'المنيو'],
                'nl' => ['menu', 'gerechten', 'prijzen', 'wat hebben', 'keuken', 'eten', 'eten'],
                'en' => ['menu', 'dishes', 'prices', 'food', 'what do you have', 'meal', 'eat'],
            ],
            'hours' => [
                'ar' => ['ساعات', 'متى تفتحون', 'اوقات العمل', 'متى مفتوح', 'مواعيد', 'ساعة', 'متى تغلقون', 'الايام'],
                'nl' => ['openingstijden', 'wanneer open', 'tijden', 'hoe laat', 'geopend', 'sluitingstijd'],
                'en' => ['hours', 'open time', 'when open', 'working hours', 'schedule', 'opening', 'closing'],
            ],
            'location' => [
                'ar' => ['اين', 'موقعكم', 'العنوان', 'المكان', 'كيف اصل', 'خريطة', 'ادريس', 'اين يقع'],
                'nl' => ['waar', 'locatie', 'adres', 'route', 'kaart', 'plaats', 'bereiken'],
                'en' => ['where', 'location', 'address', 'map', 'how to get', 'directions', 'place'],
            ],
            'contact' => [
                'ar' => ['تواصل', 'هاتف', 'واتساب', 'رقم', 'اتصل', 'بريد', 'اتصل بي', 'تواصل معي'],
                'nl' => ['contact', 'telefoon', 'whatsapp', 'nummer', 'bellen', 'email', 'bereiken'],
                'en' => ['contact', 'phone', 'whatsapp', 'number', 'call', 'email', 'reach'],
            ],
        ];

        $exact = null;
        $partial = null;
        foreach ($map as $action => $locales) {
            $keywords = $locales[$locale] ?? [];
            foreach ($keywords as $kw) {
                $kw = mb_strtolower($kw);
                if ($input === $kw) {
                    $exact = $action;
                } elseif (str_contains($input, $kw)) {
                    $partial = $action;
                }
            }
        }

        return $exact ?? $partial;
    }

    public function sendMessage(): void
    {
        $userInput = trim($this->input);
        if ($userInput === '') {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'text' => $userInput,
        ];

        $originalInput = $userInput;
        $this->input = '';
        $this->isTyping = true;

        // إذا كنا في وضع تعديل حقل أو مسار الحجز
        if ($this->currentState === 'reservation_flow' && $this->reservationStep > 0) {
            $this->handleReservationInput($originalInput);
            $this->editingField = null;
            $this->isTyping = false;

            return;
        }

        // Keyword Matching
        $matched = $this->matchKeywords($originalInput);
        if ($matched) {
            $this->isTyping = false;
            $this->handleQuickAction($matched);

            return;
        }

        $this->isTyping = false;
        $this->handleFallback();
    }

    protected function handleReservationInput(string $input): void
    {
        // محاولة استخراج رقم لحقل party_size
        if ($this->reservationStep === 1) {
            if (preg_match('/\d+/', $input, $m)) {
                $this->processPartySize((int) $m[0]);

                return;
            }
            // كلمات عددية
            $textMap = [
                'واحد' => 1, 'one' => 1, 'een' => 1, '1' => 1,
                'اثنان' => 2, 'two' => 2, 'twee' => 2, '2' => 2,
                'ثلاثة' => 3, 'three' => 3, 'drie' => 3, '3' => 3,
                'اربعة' => 4, 'أربعة' => 4, 'four' => 4, 'vier' => 4, '4' => 4,
                'خمسة' => 5, 'five' => 5, 'vijf' => 5, '5' => 5,
                'ستة' => 6, 'six' => 6, 'zes' => 6, '6' => 6,
            ];
            foreach ($textMap as $word => $num) {
                if (str_contains(mb_strtolower($input), $word)) {
                    $this->processPartySize($num);

                    return;
                }
            }
            $this->messages[] = [
                'role' => 'bot',
                'type' => 'error',
                'text' => __('messages.chatbot.reservation.party_size_invalid'),
            ];
            $this->askPartySize();

            return;
        }

        if ($this->reservationStep === 2) {
            $this->processDate($input);

            return;
        }

        if ($this->reservationStep === 3) {
            $this->processTime($input);

            return;
        }

        if ($this->reservationStep === 4) {
            $this->processName($input);

            return;
        }

        if ($this->reservationStep === 5) {
            $this->processPhone($input);

            return;
        }

        if ($this->reservationStep === 6) {
            $this->processEmail($input);

            return;
        }

        if ($this->reservationStep === 7) {
            $this->processNotes($input);

            return;
        }
    }

    public function with(): array
    {
        return [
            'categories' => $this->categories,
            'settings' => $this->settings,
            'menuItems' => $this->preorderMenuItems,
            'subtotal' => $this->preorderSubtotal,
            'tax' => $this->preorderTax,
            'total' => $this->preorderTotal,
        ];
    }
};

?>

<div>
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(140%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes bounceSoft {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(8px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulseRing {
            0% {
                box-shadow: 0 0 0 0 rgba(224, 117, 19, 0.55);
            }

            70% {
                box-shadow: 0 0 0 16px rgba(224, 117, 19, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(224, 117, 19, 0);
            }
        }

        .chatbot-fab {
            animation: pulseRing 2.4s cubic-bezier(0.24, 0, 0.38, 1) infinite;
        }

        .chatbot-window {
            animation: slideInRight 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .chat-msg {
            animation: fadeInUp 0.3s ease-out both;
        }
    </style>

    {{-- زر العوم المثبت في منتصف الحافة اليمنى للشاشة - متجاوب لجميع الأجهزة --}}
    <button
        wire:click="toggleChat"
        type="button"
        class="chatbot-fab fixed z-[70] flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-[#E07513] via-[#C8660E] to-[#8B3F05] text-white shadow-2xl shadow-[#E07513]/40 ring-2 ring-white/30 transition-all duration-300 hover:scale-110 hover:shadow-[#E07513]/60 focus:outline-none focus:ring-4 focus:ring-[#FFD700]/60 active:scale-95

           bottom-4 right-4 sm:bottom-8 sm:right-6 md:right-8
           md:top-1/2 md:-translate-y-1/2 md:bottom-auto"
        aria-label="{{ __('messages.chatbot.ui.open_chat') }}">
        @if (! $isOpen)
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            <span
                class="absolute -top-1 -right-1 h-3.5 w-3.5 rounded-full bg-emerald-400 ring-2 ring-[#260C0A] shadow-[0_0_10px_rgba(52,211,153,0.9)]"></span>
        @else
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        @endif
    </button>

    {{-- نافذة المحادثة - متجاوبة مع جميع الأجهزة --}}
    @if ($isOpen)
        <div
            class="chatbot-window fixed z-[75] flex flex-col overflow-hidden rounded-2xl border border-[#E07513]/30 bg-[#1C0705]/98 text-stone-100 shadow-2xl shadow-black/60 backdrop-blur-2xl

           inset-x-3 bottom-[92px] top-auto h-[78vh] max-h-[720px]
           sm:inset-x-6 sm:bottom-[100px]
           md:right-8 md:left-auto md:bottom-auto md:w-[420px] md:h-[640px] md:top-1/2 md:-translate-y-1/2
           lg:w-[448px]">
            {{-- رأس الشات --}}
            <div
                class="relative flex items-center gap-3 border-b border-[#E07513]/25 bg-gradient-to-r from-[#260C0A] via-[#3a1208] to-[#260C0A] px-4 py-3">
                <div
                    class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#E07513] to-[#8B3F05] ring-2 ring-[#E07513]/40 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M12 2a10 10 0 1 0 10 10 10 10 0 0 0-10-10Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <path d="M12 17h.01" />
                    </svg>
                    <span
                        class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-emerald-400 ring-2 ring-[#260C0A]"></span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-black tracking-wide text-white">
                        {{ __('messages.chatbot.ui.title') }}
                    </p>
                    <p class="truncate text-[10px] font-semibold text-[#E07513]">
                        {{ __('messages.chatbot.ui.subtitle') }}
                    </p>
                </div>

                {{-- تبديل اللغة داخل الشات --}}
                <div class="flex items-center gap-1">
                    @foreach (['ar', 'nl', 'en'] as $lang)
                        <button
                            wire:click="switchLocale('{{ $lang }}')"
                            type="button"
                            class="h-8 rounded-lg px-2 text-[10px] font-black transition-all {{ $locale === $lang ? 'bg-[#E07513] text-white shadow-md' : 'bg-white/5 text-stone-300 hover:bg-white/10' }}"
                            wire:key="lang-btn-{{ $lang }}-{{ $locale }}">
                            {{ strtoupper($lang) }}
                        </button>
                    @endforeach
                    <div class="mx-1 h-6 w-px bg-white/10"></div>
                    <button wire:click="newConversation" type="button"
                        class="h-8 w-8 rounded-lg bg-white/5 text-stone-300 transition-all hover:bg-[#E07513] hover:text-white"
                        aria-label="{{ __('messages.chatbot.ui.new_chat') }}" title="{{ __('messages.chatbot.ui.new_chat') }}">
                        <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 1 1-3-6.7L21 8" />
                            <path d="M21 3v5h-5" />
                        </svg>
                    </button>
                    <button wire:click="closeChat" type="button"
                        class="h-8 w-8 rounded-lg bg-white/5 text-stone-300 transition-all hover:bg-red-500/80 hover:text-white"
                        aria-label="{{ __('messages.chatbot.ui.close_chat') }}">
                        <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- مؤشر خطوات الحجز إن كان في مسار الحجز --}}
            @if ($currentState === 'reservation_flow' && $reservationStep > 0 || $currentState === 'summary')
                <div class="bg-black/25 px-4 py-2.5 border-b border-white/5">
                    <div class="flex items-center justify-between gap-1.5">
                        @php
                            $stepKeys = ['party_size', 'date', 'time', 'contact', 'summary'];
                        @endphp
                        @foreach ($stepKeys as $index => $key)
                            @php
                                $stepNum = $index + 1;
                                if ($currentState === 'summary') {
                                    $active = true;
                                } elseif ($reservationStep >= 4 && $key === 'contact') {
                                    $active = true;
                                } elseif ($key === 'party_size') {
                                    $active = $reservationStep === 1;
                                } elseif ($key === 'date') {
                                    $active = $reservationStep === 2;
                                } elseif ($key === 'time') {
                                    $active = $reservationStep === 3;
                                } else {
                                    $active = false;
                                }
                                if ($currentState === 'summary') {
                                    $done = true;
                                } elseif ($key === 'party_size') {
                                    $done = $reservationStep > 1;
                                } elseif ($key === 'date') {
                                    $done = $reservationStep > 2;
                                } elseif ($key === 'time') {
                                    $done = $reservationStep > 3;
                                } elseif ($key === 'contact') {
                                    $done = $reservationStep > 7;
                                } else {
                                    $done = false;
                                }
                            @endphp
                            <div class="flex flex-col items-center gap-1 flex-1 min-w-0">
                                <div
                                    class="h-7 w-7 rounded-full flex items-center justify-center text-[10px] font-black transition-all
                                    {{ $active ? 'bg-[#E07513] text-white shadow-lg shadow-[#E07513]/40 scale-105' : ($done ? 'bg-emerald-500/90 text-white' : 'bg-white/10 text-stone-400') }}">
                                    @if ($done)
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m5 12 5 5L20 7" />
                                        </svg>
                                    @else
                                        {{ $stepNum }}
                                    @endif
                                </div>
                                <span
                                    class="truncate text-[9px] font-bold {{ $active || $done ? 'text-white' : 'text-stone-500' }}">
                                    {{ __("messages.chatbot.steps_indicator.{$key}") }}
                                </span>
                            </div>
                            @if ($index < count($stepKeys) - 1)
                                <div class="h-px flex-shrink-0 flex-1 max-w-[16px] bg-white/10"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- منطقة الرسائل --}}
            <div wire:poll.visible class="flex-1 space-y-3 overflow-y-auto px-3 py-4 sm:px-4" x-data
                x-init="setTimeout(() => { const el = $el; el.scrollTop = el.scrollHeight; }, 50)"
                x-effect="setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 20)">

                @foreach ($messages as $idx => $msg)
                    <div class="chat-msg" wire:key="msg-{{ $idx }}"
                        style="animation-delay: {{ min($idx * 0.03, 0.3) }}s">
                        @if ($msg['role'] === 'user')
                            <div class="flex justify-end">
                                <div
                                    class="max-w-[85%] sm:max-w-[78%] rounded-2xl rounded-br-md bg-gradient-to-br from-[#E07513] to-[#B85709] px-3.5 py-2.5 text-sm text-white shadow-lg shadow-[#E07513]/20">
                                    <p class="leading-relaxed whitespace-pre-wrap break-words">
                                        {{ $msg['text'] }}
                                    </p>
                                </div>
                            </div>
                        @else
                            @php
                                $type = $msg['type'] ?? 'text';
                            @endphp
                            <div class="flex justify-start gap-2.5">
                                <div
                                    class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#E07513]/80 to-[#8B3F05]/80 ring-2 ring-[#E07513]/30 text-white text-xs">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M12 2a10 10 0 1 0 10 10 10 10 0 0 0-10-10Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Z" />
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                        <path d="M12 17h.01" />
                                    </svg>
                                </div>
                                <div class="min-w-0 max-w-[85%] sm:max-w-[82%] flex-1 space-y-2">

                                    {{-- نص بسيط + التنسيقات المختلفة --}}
                                    @if (in_array($type, ['text', 'info', 'success', 'error', 'warning']))
                                        <div
                                            class="rounded-2xl rounded-bl-md border px-3.5 py-2.5 text-sm leading-relaxed shadow-md whitespace-pre-wrap break-words
                                            {{ $type === 'success' ? 'border-emerald-500/30 bg-emerald-500/10 text-emerald-100' :
                                               ($type === 'error' ? 'border-red-500/30 bg-red-500/10 text-red-100' :
                                               ($type === 'warning' ? 'border-amber-500/30 bg-amber-500/10 text-amber-100' :
                                               'border-white/10 bg-white/5 text-stone-200')) }}">
                                            {!! str_replace(['**', "\n"], ['<strong class="font-black text-white">', '<br>'], e($msg['text'])) !!}
                                            @if (!str_contains($msg['text'], '</strong>'))
                                                <?php // do nothing?>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- رسالة الترحيب - مع خيارات سريعة --}}
                                    @if ($type === 'greeting')
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-3.5 py-2.5 shadow-md">
                                            <p class="text-sm text-stone-200 leading-relaxed">{{ $msg['text'] }}</p>
                                        </div>
                                        <p class="mt-3 px-1 text-[10px] font-black text-[#E07513]">
                                            {{ __('messages.chatbot.greeting.options_title') }}
                                        </p>
                                        <div class="grid grid-cols-2 gap-2">
                                            <button wire:click="handleQuickAction('reserve')" type="button"
                                                class="col-span-2 inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-3 py-2.5 text-xs font-black text-white shadow-lg shadow-[#E07513]/25 transition-all hover:-translate-y-0.5 hover:shadow-[#E07513]/40 active:scale-95">
                                                {{ __('messages.chatbot.greeting.options.reserve') }}
                                            </button>
                                            <button wire:click="handleQuickAction('menu')" type="button"
                                                class="inline-flex items-center justify-center gap-1 rounded-xl border border-white/10 bg-white/5 px-2.5 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-white/10 active:scale-95">
                                                {{ __('messages.chatbot.greeting.options.menu') }}
                                            </button>
                                            <button wire:click="handleQuickAction('hours')" type="button"
                                                class="inline-flex items-center justify-center gap-1 rounded-xl border border-white/10 bg-white/5 px-2.5 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-white/10 active:scale-95">
                                                {{ __('messages.chatbot.greeting.options.hours') }}
                                            </button>
                                            <button wire:click="handleQuickAction('location')" type="button"
                                                class="inline-flex items-center justify-center gap-1 rounded-xl border border-white/10 bg-white/5 px-2.5 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-white/10 active:scale-95">
                                                {{ __('messages.chatbot.greeting.options.location') }}
                                            </button>
                                            <button wire:click="handleQuickAction('contact')" type="button"
                                                class="inline-flex items-center justify-center gap-1 rounded-xl border border-white/10 bg-white/5 px-2.5 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-white/10 active:scale-95">
                                                {{ __('messages.chatbot.greeting.options.contact') }}
                                            </button>
                                        </div>
                                    @endif

                                    {{-- حقل إدخال party_size --}}
                                    @if ($type === 'input' && ($msg['input_type'] ?? '') === 'party_size')
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-3.5 py-2.5 shadow-md">
                                            <p class="text-sm text-stone-200 leading-relaxed">{{ $msg['text'] }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach (['solo', 'small', 'medium', 'large'] as $key)
                                                <button wire:click="selectPartySize('{{ $key }}')" type="button"
                                                    class="rounded-xl border border-[#E07513]/20 bg-[#E07513]/5 px-2.5 py-2.5 text-[11px] font-bold text-stone-100 transition-all hover:bg-[#E07513]/15 hover:border-[#E07513]/40 active:scale-95">
                                                    {{ __("messages.chatbot.reservation.party_size_options.{$key}") }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <p class="px-1 text-[10px] text-stone-500">
                                            {{ str_replace(':', '', __("messages.chatbot.ui.input_placeholder")) }} {{ __('messages.chatbot.steps_indicator.party_size') }}...
                                        </p>
                                    @endif

                                    {{-- حقل إدخال التاريخ --}}
                                    @if ($type === 'input' && ($msg['input_type'] ?? '') === 'date')
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-3.5 py-2.5 shadow-md">
                                            <p class="text-sm text-stone-200 leading-relaxed">{{ $msg['text'] }}</p>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <button wire:click="selectDatePreset('today')" type="button"
                                                class="rounded-xl border border-[#E07513]/20 bg-[#E07513]/5 px-2 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-[#E07513]/15 active:scale-95">
                                                {{ __("messages.chatbot.reservation.date_options.today") }}
                                            </button>
                                            <button wire:click="selectDatePreset('tomorrow')" type="button"
                                                class="rounded-xl border border-[#E07513]/20 bg-[#E07513]/5 px-2 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-[#E07513]/15 active:scale-95">
                                                {{ __("messages.chatbot.reservation.date_options.tomorrow") }}
                                            </button>
                                            <button wire:click="selectDatePreset('day_after')" type="button"
                                                class="rounded-xl border border-[#E07513]/20 bg-[#E07513]/5 px-2 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-[#E07513]/15 active:scale-95">
                                                {{ __("messages.chatbot.reservation.date_options.day_after") }}
                                            </button>
                                        </div>
                                        <label class="block">
                                            <input
                                                wire:change="updatedInputDate($event.target.value)"
                                                type="date"
                                                min="{{ $msg['min_date'] ?? '' }}"
                                                class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2 text-sm text-stone-100 focus:outline-none focus:ring-2 focus:ring-[#E07513]" />
                                        </label>
                                    @endif

                                    {{-- حقل إدخال الوقت --}}
                                    @if ($type === 'input' && ($msg['input_type'] ?? '') === 'time')
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-3.5 py-2.5 shadow-md">
                                            <p class="text-sm text-stone-200 leading-relaxed">{{ $msg['text'] }}</p>
                                            <p class="mt-1 text-[10px] text-stone-400">
                                                {{ __("messages.chatbot.reservation.session_duration") }}
                                            </p>
                                        </div>
                                        <div
                                            class="grid grid-cols-3 sm:grid-cols-4 gap-1.5 max-h-40 overflow-y-auto pr-1">
                                            @foreach (($msg['time_slots'] ?? []) as $slot)
                                                <button wire:click="selectTimeSlot('{{ $slot }}')" type="button"
                                                    class="rounded-lg border border-[#E07513]/20 bg-[#E07513]/5 px-1.5 py-1.5 text-[11px] font-bold text-stone-100 transition-all hover:bg-[#E07513] hover:text-white active:scale-95">
                                                    {{ $slot }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- حقول نص عادي (name, phone, email) --}}
                                    @if ($type === 'input' && in_array($msg['input_type'] ?? '', ['text', 'tel', 'email', 'textarea']))
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-3.5 py-2.5 shadow-md">
                                            <p class="text-sm text-stone-200 leading-relaxed">{{ $msg['text'] }}</p>
                                            @if (($msg['optional'] ?? false))
                                                <p class="mt-1 text-[10px] text-stone-400">
                                                    ({{ mb_strtoupper($locale) === 'AR' ? 'اختياري' : ($locale === 'nl' ? 'Optioneel' : 'Optional') }})
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- بطاقة الملخص مع أزرار التعديل الفردية --}}
                                    @if ($type === 'summary')
                                        <div
                                            class="rounded-2xl border border-[#E07513]/40 bg-gradient-to-br from-[#E07513]/10 via-[#260C0A]/80 to-black/40 p-4 shadow-2xl">
                                            <div
                                                class="mb-3 flex items-center gap-2 border-b border-[#E07513]/25 pb-2">
                                                <svg class="h-5 w-5 text-[#E07513]" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path
                                                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" />
                                                    <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                                                </svg>
                                                <p class="text-sm font-black text-white">
                                                    {{ $msg['text'] }}
                                                </p>
                                            </div>
                                            <ul class="space-y-2 text-sm">
                                                @php
                                                    $fMap = [
                                                        'customer_name' => 'name',
                                                        'customer_phone' => 'phone',
                                                        'customer_email' => 'email',
                                                        'reservation_date' => 'date',
                                                        'reservation_time' => 'time',
                                                        'party_size' => 'party_size',
                                                        'special_requests' => 'notes',
                                                    ];
                                                @endphp
                                                @foreach ($fMap as $key => $label)
                                                    @php
                                                        $val = $reservationData[$key] ?? null;
                                                        if (! $val && $key !== 'special_requests' && $key !== 'customer_email') {
                                                            continue;
                                                        }
                                                        if ($key === 'reservation_date') {
                                                            try {
                                                                $val = \Carbon\Carbon::parse($val)->locale($locale)->isoFormat('LL');
                                                            } catch (\Throwable $e) {}
                                                        }
                                                    @endphp
                                                    <li
                                                        class="flex items-start justify-between gap-2 rounded-lg px-2 py-1.5 transition-colors hover:bg-white/5">
                                                        <div class="min-w-0 flex-1">
                                                            <span class="text-[10px] font-bold uppercase text-[#E07513]">
                                                                {{ __("messages.chatbot.summary.fields.{$label}") }}
                                                            </span>
                                                            <p class="truncate text-[13px] text-stone-100">
                                                                {{ $val ?: ($key === 'customer_email' || $key === 'special_requests' ? '—' : '') }}
                                                            </p>
                                                        </div>
                                                        <button wire:click="editField('{{ $key }}')"
                                                            type="button"
                                                            class="shrink-0 rounded-lg bg-white/5 px-2 py-1 text-[10px] font-bold text-stone-200 transition-all hover:bg-[#E07513] hover:text-white active:scale-95">
                                                            ✏️ {{ __("messages.chatbot.summary.edit") }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="mt-4 grid grid-cols-1 gap-2">
                                                <button wire:click="confirmReservation" type="button"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-3 py-3 text-sm font-black text-white shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5 hover:shadow-emerald-500/40 active:scale-95">
                                                    ✓ {{ __("messages.chatbot.summary.confirm") }}
                                                </button>
                                                <button wire:click="startPreorder" type="button"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-xl border-2 border-[#E07513]/60 bg-[#E07513]/10 px-3 py-3 text-sm font-black text-[#FFD700] transition-all hover:bg-[#E07513]/25 active:scale-95">
                                                    🛒 {{ __("messages.chatbot.summary.add_preorder") }}
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- بطاقة نجاح الحجز مع كود المرجع --}}
                                    @if ($type === 'success_block')
                                        <div
                                            class="rounded-2xl border border-emerald-500/40 bg-gradient-to-br from-emerald-500/15 via-[#062918]/80 to-black/40 p-4 shadow-2xl">
                                            <div class="mb-3 flex items-center gap-2">
                                                <div
                                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500/20 ring-2 ring-emerald-400/40">
                                                    <svg class="h-6 w-6 text-emerald-300" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm font-black text-emerald-100">{{ $msg['text'] }}</p>
                                            </div>
                                            <div class="rounded-xl bg-black/40 p-3 text-center ring-1 ring-emerald-500/20">
                                                <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">
                                                    {{ __("messages.chatbot.success.reference") }}
                                                </p>
                                                <p x-data="{ copied:false }"
                                                    x-on:click="navigator.clipboard.writeText('{{ $msg['reference_code'] }}'); copied = true; setTimeout(()=>copied=false,2000)"
                                                    class="mt-1 select-all cursor-pointer text-xl font-black tracking-[0.15em] text-[#FFD700] font-['Plus_Jakarta_Sans'] transition-colors hover:text-white">
                                                    {{ $msg['reference_code'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- سؤال الطلب المسبق بعد نجاح الحجز --}}
                                    @if ($type === 'preorder_prompt')
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-[#FFD700]/20 bg-[#FFD700]/5 px-3.5 py-2.5">
                                            <p class="text-sm leading-relaxed text-[#FFD700]">{{ $msg['text'] }}</p>
                                        </div>
                                        <div class="grid grid-cols-1 gap-2">
                                            <button wire:click="startPreorder" type="button"
                                                class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-3 py-2.5 text-xs font-black text-white shadow-lg shadow-[#E07513]/25 transition-all hover:-translate-y-0.5 active:scale-95">
                                                {{ __("messages.chatbot.success.preorder_yes") }}
                                            </button>
                                            <button wire:click="skipPreorder" type="button"
                                                class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-xs font-bold text-stone-200 transition-all hover:bg-white/10 active:scale-95">
                                                {{ __("messages.chatbot.success.preorder_no") }}
                                            </button>
                                        </div>
                                    @endif

                                    {{-- واجهة اختيار الطلب المسبق للأطباق --}}
                                    @if ($type === 'preorder_intro')
                                        <div
                                            class="rounded-2xl border border-[#E07513]/30 bg-gradient-to-br from-[#E07513]/8 via-[#260C0A]/90 to-black/40 p-3.5 shadow-2xl">
                                            <p class="mb-3 text-sm font-bold text-stone-100 leading-relaxed">
                                                {{ $msg['text'] }}
                                            </p>

                                            {{-- فلاتر الأقسام والمميز --}}
                                            <div class="mb-3 flex flex-wrap gap-1.5">
                                                <button wire:click="$toggle('showFeaturedOnly')" type="button"
                                                    class="rounded-full px-2.5 py-1.5 text-[10px] font-black transition-all
                                                    {{ $showFeaturedOnly ? 'bg-[#FFD700] text-[#260C0A] shadow-md' : 'bg-white/5 text-stone-200 border border-white/10 hover:bg-white/10' }}">
                                                    ⭐ {{ __("messages.chatbot.preorder.featured") }}
                                                </button>
                                                <button wire:click="setPreorderCategory(null)" type="button"
                                                    class="rounded-full px-2.5 py-1.5 text-[10px] font-black transition-all
                                                    {{ is_null($preorderCategory) ? 'bg-[#E07513] text-white shadow-md' : 'bg-white/5 text-stone-200 border border-white/10 hover:bg-white/10' }}">
                                                    {{ __("messages.chatbot.preorder.all") }}
                                                </button>
                                                @foreach ($categories as $cat)
                                                    @php
                                                        $n = "name_{$locale}";
                                                    @endphp
                                                    <button wire:click="setPreorderCategory({{ $cat->id }})"
                                                        type="button"
                                                        class="truncate rounded-full px-2.5 py-1.5 text-[10px] font-black transition-all max-w-[120px]
                                                        {{ $preorderCategory === $cat->id ? 'bg-[#E07513] text-white shadow-md' : 'bg-white/5 text-stone-200 border border-white/10 hover:bg-white/10' }}">
                                                        {{ $cat->$n }}
                                                    </button>
                                                @endforeach
                                            </div>

                                            {{-- قائمة الأطباق --}}
                                            <div
                                                class="grid grid-cols-1 gap-2 max-h-[280px] overflow-y-auto pr-1">
                                                @foreach ($menuItems as $mItem)
                                                    @php
                                                        $n = "name_{$locale}";
                                                        $d = "description_{$locale}";
                                                        $inCart = $preorderCart[$mItem->id]['quantity'] ?? 0;
                                                    @endphp
                                                    <div
                                                        class="group flex gap-2 rounded-xl border border-white/10 bg-black/25 p-2 transition-all hover:border-[#E07513]/30 hover:bg-black/40">
                                                        @if ($mItem->image)
                                                            <div
                                                                class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-[#260C0A]">
                                                                <img src="{{ asset('storage/'.$mItem->image) }}"
                                                                    alt=""
                                                                    class="h-full w-full object-cover"
                                                                    onerror="this.style.display='none'">
                                                            </div>
                                                        @else
                                                            <div
                                                                class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-[#E07513]/30 to-[#8B3F05]/30 text-2xl">
                                                                🍽️
                                                            </div>
                                                        @endif
                                                        <div class="min-w-0 flex-1 flex flex-col">
                                                            <div class="flex items-start justify-between gap-2">
                                                                <div class="min-w-0 flex-1">
                                                                    <p class="truncate text-[12px] font-black text-white">
                                                                        {{ $mItem->$n }}
                                                                        @if ($mItem->is_featured)
                                                                            <span
                                                                                class="ms-1 text-[9px] text-[#FFD700]">⭐</span>
                                                                        @endif
                                                                    </p>
                                                                    @if (!empty($mItem->$d))
                                                                        <p
                                                                            class="mt-0.5 line-clamp-2 text-[10px] leading-relaxed text-stone-400">
                                                                            {{ $mItem->$d }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                                <p
                                                                    class="shrink-0 text-[12px] font-black text-[#FFD700]">
                                                                    €{{ number_format((float)$mItem->price, 2) }}
                                                                </p>
                                                            </div>
                                                            <div class="mt-auto flex items-center justify-end gap-1 pt-1">
                                                                @if ($inCart > 0)
                                                                    <button
                                                                        wire:click="removeFromPreorder({{ $mItem->id }})"
                                                                        type="button"
                                                                        class="h-7 w-7 rounded-lg bg-red-500/20 text-red-300 hover:bg-red-500/30 transition-colors text-sm font-black">
                                                                        −
                                                                    </button>
                                                                    <span
                                                                        class="min-w-[26px] text-center text-[11px] font-black text-white">
                                                                        {{ $inCart }}
                                                                    </span>
                                                                @endif
                                                                <button
                                                                    wire:click="addToPreorder({{ $mItem->id }})"
                                                                    type="button"
                                                                    class="h-7 rounded-lg bg-gradient-to-r from-[#E07513] to-[#B85709] px-3 text-[11px] font-black text-white shadow-md hover:brightness-110 transition-all active:scale-95">
                                                                    {{ $inCart > 0 ? '+1' : __("messages.chatbot.preorder.add") }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- سلة الطلب المسبق --}}
                                            <div
                                                class="mt-3 rounded-xl border border-[#E07513]/30 bg-black/40 p-3 ring-1 ring-[#E07513]/20">
                                                <div class="mb-2 flex items-center justify-between">
                                                    <p class="text-xs font-black text-[#E07513]">
                                                        🛒 {{ __("messages.chatbot.preorder.cart") }}
                                                    </p>
                                                    @if (!empty($preorderCart))
                                                        <span
                                                            class="rounded-full bg-[#E07513] px-2 py-0.5 text-[10px] font-black text-white">
                                                            {{ collect($preorderCart)->sum('quantity') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if (empty($preorderCart))
                                                    <p class="text-[11px] text-stone-400 italic text-center py-2">
                                                        {{ __("messages.chatbot.preorder.cart_empty") }}
                                                    </p>
                                                @else
                                                    <ul class="space-y-1.5 mb-2 max-h-28 overflow-y-auto pr-1">
                                                        @foreach ($preorderCart as $cart)
                                                            <li
                                                                class="flex items-center justify-between gap-2 text-[11px]">
                                                                <span class="truncate text-stone-200">
                                                                    <span
                                                                        class="font-black text-[#E07513]">{{ $cart['quantity'] }}×</span>
                                                                    {{ $cart['name'] }}
                                                                </span>
                                                                <span class="shrink-0 font-black text-[#FFD700]">
                                                                    €{{ number_format($cart['unit_price'] * $cart['quantity'], 2) }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <div class="space-y-1 border-t border-white/10 pt-2 text-[11px]">
                                                        <div class="flex justify-between text-stone-300">
                                                            <span>{{ __("messages.chatbot.preorder.subtotal") }}</span>
                                                            <span>€{{ number_format($subtotal, 2) }}</span>
                                                        </div>
                                                        <div class="flex justify-between text-stone-400">
                                                            <span>{{ __("messages.chatbot.preorder.tax") }}</span>
                                                            <span>€{{ number_format($tax, 2) }}</span>
                                                        </div>
                                                        <div
                                                            class="mt-1 flex justify-between border-t border-[#FFD700]/20 pt-1.5 text-sm font-black text-[#FFD700]">
                                                            <span>{{ __("messages.chatbot.preorder.total") }}</span>
                                                            <span>€{{ number_format($total, 2) }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="mt-3 grid grid-cols-1 gap-2">
                                                    <button wire:click="confirmPreorder" type="button"
                                                        @disabled(empty($preorderCart))
                                                        class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-3 py-2.5 text-xs font-black text-white shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5 disabled:opacity-50 disabled:hover:translate-y-0 disabled:cursor-not-allowed active:scale-95">
                                                        ✓ {{ __("messages.chatbot.preorder.confirm") }}
                                                    </button>
                                                    <button wire:click="skipPreorder" type="button"
                                                        class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-[11px] font-bold text-stone-300 transition-all hover:bg-white/10 active:scale-95">
                                                        {{ __("messages.chatbot.preorder.skip") }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- معلومات المنيو --}}
                                    @if ($type === 'menu_info')
                                        <div
                                            class="rounded-2xl border border-white/10 bg-white/5 px-3.5 py-2.5 shadow-md">
                                            <p class="text-sm text-stone-200 leading-relaxed">{{ $msg['text'] }}</p>
                                        </div>
                                        <a href="#menu" wire:navigate
                                            class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-[#E07513] to-[#B85709] px-3 py-2 text-xs font-black text-white shadow-lg shadow-[#E07513]/25 transition-all hover:-translate-y-0.5 active:scale-95">
                                            {{ __("messages.chatbot.keyword_responses.menu_view_full") }}
                                        </a>
                                    @endif

                                    {{-- معلومات مع زر إجراء خارجي --}}
                                    @if ($type === 'info_with_action')
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-3.5 py-2.5 shadow-md whitespace-pre-wrap break-words">
                                            <p class="text-sm leading-relaxed text-stone-200">
                                                {!! str_replace(['**', "\n"], ['<strong class="font-black text-white">', '<br>'], e($msg['text'])) !!}
                                            </p>
                                        </div>
                                        <a href="{{ $msg['action_href'] ?? '#' }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 px-3 py-2 text-xs font-black text-white shadow-lg transition-all hover:-translate-y-0.5 active:scale-95">
                                            🗺️ {{ $msg['action_label'] }}
                                        </a>
                                    @endif

                                    {{-- Fallback --}}
                                    @if ($type === 'fallback')
                                        <div
                                            class="rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-3.5 py-2.5">
                                            <p class="text-sm leading-relaxed text-stone-200">{{ $msg['text'] }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach (['reserve', 'menu', 'hours', 'location', 'contact'] as $act)
                                                <button wire:click="handleQuickAction('{{ $act }}')" type="button"
                                                    class="rounded-xl border border-white/10 bg-white/5 px-2.5 py-2 text-[11px] font-bold text-stone-100 transition-all hover:bg-white/10 active:scale-95">
                                                    {{ __("messages.chatbot.fallback.options.{$act}") }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <p class="px-1 text-[10px] italic text-stone-500">
                                            {{ __("messages.chatbot.fallback.retry_prompt") }}
                                        </p>
                                    @endif

                                    {{-- Fallback متكرر مع عرض واتساب --}}
                                    @if ($type === 'fallback_stuck')
                                        <div
                                            class="rounded-2xl border border-amber-500/30 bg-amber-500/10 p-3.5 shadow-md">
                                            <p class="text-sm font-black text-amber-100">{{ $msg['text'] }}</p>
                                            <p class="mt-1 text-xs text-amber-200">{{ $msg['subtitle'] }}</p>
                                            <a href="{{ $msg['whatsapp_href'] ?? '#' }}" target="_blank"
                                                rel="noopener noreferrer"
                                                class="mt-3 inline-flex items-center justify-center w-full gap-1.5 rounded-xl border border-emerald-400/30 bg-emerald-500/20 px-3 py-2 text-xs font-black text-emerald-200 transition-all hover:bg-emerald-500/30 active:scale-95">
                                                💬 {{ __("messages.chatbot.keyword_responses.whatsapp_cta") }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                @if ($isTyping)
                    <div class="chat-msg flex justify-start gap-2.5">
                        <div
                            class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#E07513]/80 to-[#8B3F05]/80 ring-2 ring-[#E07513]/30 text-white text-xs">
                            🤖
                        </div>
                        <div
                            class="flex items-center gap-1.5 rounded-2xl rounded-bl-md border border-white/10 bg-white/5 px-4 py-3">
                            <span
                                class="h-2 w-2 rounded-full bg-[#E07513]"
                                style="animation: bounceSoft 1.1s ease-in-out infinite 0s;"></span>
                            <span
                                class="h-2 w-2 rounded-full bg-[#E07513]"
                                style="animation: bounceSoft 1.1s ease-in-out infinite 0.15s;"></span>
                            <span
                                class="h-2 w-2 rounded-full bg-[#E07513]"
                                style="animation: bounceSoft 1.1s ease-in-out infinite 0.3s;"></span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- منطقة الإدخال السفلية --}}
            <form wire:submit="sendMessage"
                class="border-t border-[#E07513]/25 bg-gradient-to-t from-[#260C0A] via-[#260C0A]/95 to-[#260C0A]/80 px-3 py-3 sm:px-4">
                <div class="flex items-end gap-2">
                    <textarea
                        wire:model="input"
                        rows="1"
                        placeholder="{{ __('messages.chatbot.ui.input_placeholder') }}"
                        class="flex-1 resize-none rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-stone-100 placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-[#E07513]/60 focus:border-[#E07513]/60 transition-all min-h-[44px] max-h-[120px]"></textarea>
                    <button type="submit"
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#E07513] to-[#B85709] text-white shadow-lg shadow-[#E07513]/25 transition-all hover:-translate-y-0.5 hover:shadow-[#E07513]/50 active:scale-95 disabled:opacity-60 disabled:hover:translate-y-0"
                        aria-label="{{ __('messages.chatbot.ui.send') }}"
                        @disabled(trim($input) === '')>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 2 11 13M22 2l-7 20-4-9-9-4Z" />
                        </svg>
                    </button>
                </div>
                <p class="mt-1.5 text-center text-[9px] text-stone-500">
                    🔒 {{ strtoupper($locale) === 'AR' ? 'البيانات مشفرة ومحمية' : ($locale === 'nl' ? 'Gegevens beveiligd' : 'Data protected') }}
                </p>
            </form>
        </div>
    @endif
</div>
