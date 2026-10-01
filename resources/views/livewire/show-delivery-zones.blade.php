{{--
    ملف عرض مكوّن ShowDeliveryZones
    - يعرض جدولًا بمناطق التوصيل بتصميم يعكس هوية المطبخ اليمني.
    - الألوان المستخدمة مستوحاة من الشعار:
        البني الداكن: #4a1c1c
        البرتقالي الذهبي: #e08a3c
        الأبيض: #ffffff
    - يعتمد على الخاصية المحسوبة $this->deliveryZones القادمة من المكوّن.
--}}

<div class="container mx-auto p-6" dir="rtl"   id="delivery.zones">

    {{-- عنوان الصفحة بلون بني داكن مع خط ذهبي أسفله --}}
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-[#4a1c1c] inline-block pb-2 border-b-4 border-[#e08a3c]">
            مناطق وأسعار التوصيل
        </h2>
    </div>

    <div class="overflow-x-auto shadow-xl rounded-xl border-2 border-[#e08a3c]">

        <table class="min-w-full">

            {{-- رأس الجدول: خلفية بنية داكنة بنص أبيض وأسفل ذهبي --}}
            <thead>
                <tr class="bg-[#4a1c1c] text-white">
                    <th class="py-4 px-6 text-right text-sm font-semibold uppercase tracking-wider border-b-4 border-[#e08a3c]">
                        المنطقة
                    </th>
                    <th class="py-4 px-6 text-right text-sm font-semibold uppercase tracking-wider border-b-4 border-[#e08a3c]">
                        الرمز البريدي
                    </th>
                    <th class="py-4 px-6 text-right text-sm font-semibold uppercase tracking-wider border-b-4 border-[#e08a3c]">
                        تكلفة التوصيل
                    </th>
                </tr>
            </thead>

            {{-- جسم الجدول: صفوف بيضاء مع تناوب كريمي فاتح عند التمرير --}}
            <tbody class="bg-white">
                @forelse ($this->deliveryZones as $zone)
                    <tr wire:key="zone-{{ $zone->id }}"
                        class="border-b border-[#e08a3c]/30 transition-colors duration-200 hover:bg-[#fdf3e7]">

                        {{-- اسم المنطقة بلون بني داكن --}}
                        <td class="py-4 px-6 text-right whitespace-nowrap font-medium text-[#4a1c1c]">
                            {{ $zone->region_name }}
                        </td>

                        {{-- الرمز البريدي بلون رمادي غامق --}}
                        <td class="py-4 px-6 text-right text-gray-700">
                            {{ $zone->postal_code }}
                        </td>

                        {{-- التكلفة داخل وسم (Badge) برتقالي أنيق --}}
                        <td class="py-4 px-6 text-right">
                            <span class="inline-block bg-[#e08a3c] text-white font-bold px-4 py-1 rounded-full shadow-sm">
                                €{{ number_format((float) $zone->delivery_cost, 2) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    {{-- رسالة عند عدم وجود بيانات --}}
                    <tr>
                        <td colspan="3" class="py-10 px-6 text-center text-[#4a1c1c] font-medium">
                            لا توجد مناطق توصيل متاحة حالياً.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    {{-- مؤشر تحميل بنفس الهوية --}}
    <div wire:loading class="text-center text-[#e08a3c] font-semibold mt-4">
        جارٍ التحميل...
    </div>
</div>