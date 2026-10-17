{{-- نافذة السلة: تعتمد على Alpine للتحديث الفوري (إضافة/حذف/تغيير كمية) وLivewire يحفظ في الخلفية.
     ملف واحد مستقل: لا يحتاج أي store أو تعديل على ملفات أخرى.
     resources/views/livewire/menu/cart/modal.blade.php --}}
<div x-show="isCartOpen" x-cloak
    x-data="{
        rows: [],      // [{ id, name, price, quantity }]
        pending: 0,    // عدد طلبات Livewire الجارية
        maxQty: Number(@js($this->maxQuantityPerItem)),   // الحد الأقصى من إعدادات المطعم
        maxTitle: @js(__('cart.max_quantity.title')),
        maxMessage: @js(__('cart.max_quantity.message')),

        get count() { return this.rows.reduce((s, r) => s + r.quantity, 0); },
        money(n) { return '€' + Number(n).toFixed(2); },
        find(id) { return this.rows.find(r => r.id === Number(id)); },

        fromServer(cart) {
            return Object.entries(cart || {}).map(([id, i]) => ({
                id: Number(id),
                name: i.name,
                price: Number(i.price),
                quantity: Number(i.quantity),
            }));
        },

        // تغيير الكمية: يتغير العرض فوراً ثم يُحفظ في الخادم
        change(id, delta) {
            const row = this.find(id);
            if (!row) return;
            const next = row.quantity + delta;
            if (next < 1) return this.remove(id);
            // 🚫 تجاوز الحد الأقصى: نُخبر العميل ولا نرسل أي طلب
            if (next > this.maxQty) return this.warnMax(row);
            row.quantity = next;
            this.run('updateQuantity', [id, delta]);
        },

        // تنبيه العميل بأنه وصل للحد الأقصى (نفس رسالة الخادم)
        warnMax(row) {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    title: this.maxTitle,
                    message: this.maxMessage.replace(':name', row.name).replace(':max', this.maxQty),
                }
            }));
        },

        // حذف: يختفي الصف فوراً
        remove(id) {
            this.rows = this.rows.filter(r => r.id !== Number(id));
            this.run('removeFromCart', [id]);
        },

        // استدعاء Livewire؛ عند انتهاء آخر طلب نعتمد حالة الخادم (يصحح العرض تلقائياً عند الفشل)
        run(method, args) {
            this.pending++;
            this.$wire[method](...args)
                .catch(() => {})
                .finally(() => {
                    this.pending--;
                    if (this.pending === 0) this.rows = this.fromServer(this.$wire.cart);
                });
        }
    }"
    x-init="
        rows = fromServer(@js($cart));
        // أي تغيير يأتي من الخادم (مثل الإضافة من بطاقات الأطباق) ينعكس هنا
        $wire.$watch('cart', (v) => { if (pending === 0) rows = fromServer(v); });
    "
    class="fixed inset-0 z-50 overflow-hidden"
    dir="{{ app()->isLocale('ar') ? 'rtl' : 'ltr' }}"
    role="dialog" aria-modal="true" aria-labelledby="cart-modal-title"
    @keydown.escape.window="if (!document.querySelector('[data-my-coupons][data-open=true]')) isCartOpen = false">

    {{-- الخلفية --}}
    <div x-show="isCartOpen"
        x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-stone-900/70 backdrop-blur-sm" @click="isCartOpen = false" aria-hidden="true">
    </div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-0 sm:pl-10">
        <div x-show="isCartOpen"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md md:max-w-lg bg-[#FAF6F0] text-[#2C1810] shadow-2xl flex flex-col justify-between border-l border-[#E8DFD3]">

            {{-- رأس النافذة --}}
            <div
                class="p-5 bg-gradient-to-r from-[#2A0D0A] to-[#1A0604] text-white flex items-center justify-between border-b border-[#E07513]/30 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center">
                        <x-lucide-crown class="w-5 h-5" aria-hidden="true" />
                    </div>
                    <div>
                        <h3 id="cart-modal-title" class="font-black text-base text-white">
                            {{ __('messages.menu.cart') ?? 'سلة الأطباق وتثبيت الحجز' }}</h3>
                        <p class="text-xs text-amber-200">
                            <span x-text="count"></span>
                            {{ __('messages.menu.items') ?? 'أصناف مختارة' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    {{-- كوبوناتي: يجلب بيانات من الخادم --}}
                    <button type="button" @click="$dispatch('open-my-coupons')"
                        class="text-amber-200 hover:text-white hover:bg-white/10 rounded-full p-2 transition-colors"
                        title="{{ __('my-coupons.title') }}" aria-label="{{ __('my-coupons.title') }}">
                        <x-lucide-ticket-percent class="w-5 h-5" aria-hidden="true" />
                    </button>

                    {{-- إغلاق فوري --}}
                    <button type="button" @click="isCartOpen = false" aria-label="{{ __('messages.menu.close') ?? 'Close' }}"
                        class="text-stone-400 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10">
                        <x-lucide-x class="w-5 h-5" aria-hidden="true" />
                    </button>
                </div>
            </div>

            {{-- المحتوى القابل للتمرير --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-5">

                {{-- السلة فارغة --}}
                <div x-show="rows.length === 0"
                    class="flex flex-col items-center justify-center py-12 text-center">
                    <div
                        class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mb-4 border-2 border-stone-200">
                        <x-lucide-shopping-cart class="w-10 h-10 text-stone-300" aria-hidden="true" />
                    </div>
                    <h3 class="text-sm font-black text-stone-700 mb-2">
                        {{ __('messages.menu.empty') ?? 'السلة فارغة' }}</h3>
                    <p class="text-xs text-stone-500 max-w-[200px] mb-6">{{ __('messages.menu.emptyText') }}</p>
                    <button type="button" @click="isCartOpen = false"
                        class="w-full py-3 bg-[#2A0D0A] hover:bg-[#E07513] text-white font-bold rounded-xl text-sm transition-colors">
                        {{ __('messages.menu.browseMenu') }}
                    </button>
                </div>

                {{-- السلة فيها أطباق --}}
                <div x-show="rows.length > 0" class="space-y-5">
                    <div>
                        <h4 class="font-bold text-xs text-stone-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <x-lucide-utensils class="w-4 h-4" aria-hidden="true" />
                            {{ __('messages.menu.selectedDishes') ?? 'الأطباق المختارة للوليمة' }}
                        </h4>

                        <div class="space-y-3">
                            <template x-for="row in rows" :key="row.id">
                                <div
                                    class="bg-white p-3 rounded-xl border border-stone-200 flex items-center justify-between shadow-sm hover:border-amber-200 transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-xs text-[#2C0D0A] truncate" x-text="row.name"></div>
                                        <div class="text-xs font-black text-[#E07513] mt-0.5"
                                            x-text="money(row.price * row.quantity)"></div>
                                    </div>

                                    <div class="flex items-center gap-2 bg-stone-50 p-1 rounded-lg border border-stone-200">
                                        <button type="button" @click="remove(row.id)"
                                            class="w-7 h-7 flex items-center justify-center text-stone-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors active:scale-90"
                                            title="{{ __('messages.menu.removeFromCart') }}">
                                            <x-lucide-trash-2 class="w-3 h-3" aria-hidden="true" />
                                        </button>

                                        <div class="w-px h-5 bg-stone-300"></div>

                                        <button type="button" @click="change(row.id, -1)"
                                            class="w-7 h-7 bg-white rounded text-stone-900 hover:bg-red-50 hover:text-red-600 flex items-center justify-center font-bold text-sm shadow-sm transition-colors active:scale-90">−</button>

                                        <span class="w-5 text-center text-xs font-black text-[#2C0D0A]"
                                            x-text="row.quantity"></span>

                                        <button type="button" @click="change(row.id, 1)"
                                            class="w-7 h-7 bg-[#E07513] text-white rounded hover:bg-amber-600 flex items-center justify-center font-bold text-sm shadow-sm transition-colors active:scale-90">+</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    @include('livewire.menu.cart.checkout-form')
                </div>
            </div>
        </div>
    </div>
</div>