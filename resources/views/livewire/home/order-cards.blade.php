{{--
    شبكة بطاقات الطلب: تعرض ثلاثة أعمدة على الشاشات المتوسطة وما فوق، وتكرر ملف order-card لكل عنصر في orderOptions مع تمرير فهرسه لبناء wire:key ثابت لضمان تحديث Livewire الصحيح.
--}}

<div class="mt-10 grid gap-6 md:grid-cols-3">

    {{-- حلقة تمرر العنصر الحالي ولغة العرض وفهرسه إلى ملف البطاقة الواحدة --}}
    @foreach ($orderOptions as $option)
        @include('livewire.home.order-card', [
            'option' => $option,
            'isArabic' => $isArabic,
            'index' => $loop->index,
        ])
    @endforeach
</div>
