{{--
    قالب التذييل الكامل: خلفية تراثية، ترويسة ترحيبية، بطاقة الطبق المميز،
    أربع بطاقات معلومات، وشريط الحقوق. يعتمد على بيانات مكوّن Footer
    ويدعم الاتجاهين، ويستخدم Alpine لتمييز اليوم الحالي وتبديل اللغة.
--}}
<footer id="footer" dir="{{ $dir }}" lang="{{ $locale }}" x-data="{ today: new Date().getDay() }"
    class="relative isolate overflow-hidden bg-[#e9c9a6] text-[#3b2418]">

    <div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-10 lg:py-12">

        {{-- الجزء العلوي: الخلفية والترويسة والطبق المميز --}}
        @include('livewire.footer.footer-top')

        {{-- عنوان قسم تفاصيل الزيارة والتواصل --}}
        <div class="mt-8 flex justify-end">
            <span class="rounded-full bg-white/80 px-4 py-2 text-xs font-black text-[#2b1810] shadow-sm backdrop-blur">
                {{ __('footer.visit_details') }}
            </span>
        </div>

        {{-- شبكة البطاقات الأربع المتجاوبة --}}
        <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- @include('livewire.footer.footer-hours') --}}
            @include('livewire.footer.footer-contact')
        </div>

        {{-- شريط الحقوق السفلي --}}
        @include('livewire.footer.footer-bottom')
    </div>
</footer>
