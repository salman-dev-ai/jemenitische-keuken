<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول حقل :attribute.',
    'accepted_if' => 'يجب قبول حقل :attribute عندما يكون :other هو :value.',
    'active_url' => 'يجب أن يكون حقل :attribute رابطًا صالحًا.',
    'after' => 'يجب أن يكون حقل :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا لاحقًا أو مطابقًا لـ :date.',
    'alpha' => 'يجب أن يحتوي حقل :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي حقل :attribute على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي حقل :attribute على أحرف وأرقام فقط.',
    'any_of' => 'حقل :attribute غير صالح.',
    'array' => 'يجب أن يكون حقل :attribute مصفوفة.',
    'ascii' => 'يجب أن يحتوي حقل :attribute على أحرف أبجدية رقمية ورموز أحادية البايت فقط.',
    'before' => 'يجب أن يكون حقل :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا قبل أو يساوي :date.',
    'between' => [
        'array' => 'يجب أن يحتوي حقل :attribute على عدد من العناصر بين :min و :max.',
        'file' => 'يجب أن يكون حجم حقل :attribute بين :min و :max كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute بين :min و :max.',
        'string' => 'يجب أن يكون طول حقل :attribute بين :min و :max حرفًا.',
    ],
    'boolean' => 'يجب أن تكون قيمة حقل :attribute صحيحة أو خاطئة.',
    'can' => 'يحتوي حقل :attribute على قيمة غير مصرح بها.',
    'confirmed' => 'تأكيد حقل :attribute غير متطابق.',
    'contains' => 'حقل :attribute يفتقد قيمة مطلوبة.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'يجب أن يكون حقل :attribute تاريخًا صحيحًا.',
    'date_equals' => 'يجب أن يكون حقل :attribute تاريخًا مساويًا لـ :date.',
    'date_format' => 'يجب أن يتطابق حقل :attribute مع الصيغة :format.',
    'decimal' => 'يجب أن يحتوي حقل :attribute على :decimal منازل عشرية.',
    'declined' => 'يجب رفض حقل :attribute.',
    'declined_if' => 'يجب رفض حقل :attribute عندما يكون :other هو :value.',
    'different' => 'يجب أن يكون حقل :attribute و :other مختلفين.',
    'digits' => 'يجب أن يتكون حقل :attribute من :digits أرقام.',
    'digits_between' => 'يجب أن يتكون حقل :attribute من عدد أرقام بين :min و :max.',
    'dimensions' => 'يحتوي حقل :attribute على أبعاد صورة غير صالحة.',
    'distinct' => 'يحتوي حقل :attribute على قيمة مكررة.',
    'doesnt_contain' => 'يجب ألا يحتوي حقل :attribute على أي مما يلي: :values.',
    'doesnt_end_with' => 'يجب ألا ينتهي حقل :attribute بأحد مما يلي: :values.',
    'doesnt_start_with' => 'يجب ألا يبدأ حقل :attribute بأحد مما يلي: :values.',
    'email' => 'يجب أن يكون حقل :attribute بريدًا إلكترونيًا صحيحًا.',
    'encoding' => 'يجب أن يكون حقل :attribute مشفرًا بـ :encoding.',
    'ends_with' => 'يجب أن ينتهي حقل :attribute بأحد مما يلي: :values.',
    'enum' => 'القيمة المختارة لـ :attribute غير صالحة.',
    'exists' => 'القيمة المختارة لـ :attribute غير صالحة.',
    'extensions' => 'يجب أن يكون حقل :attribute ملفًا بامتداد واحد من: :values.',
    'file' => 'يجب أن يكون حقل :attribute ملفًا.',
    'filled' => 'يجب أن يحتوي حقل :attribute على قيمة.',
    'gt' => [
        'array' => 'يجب أن يحتوي حقل :attribute على أكثر من :value عنصرًا.',
        'file' => 'يجب أن يكون حجم حقل :attribute أكبر من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من :value.',
        'string' => 'يجب أن يكون طول حقل :attribute أكبر من :value حرفًا.',
    ],
    'gte' => [
        'array' => 'يجب أن يحتوي حقل :attribute على :value عنصرًا أو أكثر.',
        'file' => 'يجب أن يكون حجم حقل :attribute أكبر من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أكبر من أو تساوي :value.',
        'string' => 'يجب أن يكون طول حقل :attribute أكبر من أو يساوي :value حرفًا.',
    ],
    'hex_color' => 'يجب أن يكون حقل :attribute لونًا سداسيًا صالحًا.',
    'image' => 'يجب أن يكون حقل :attribute صورة.',
    'in' => 'القيمة المختارة لـ :attribute غير صالحة.',
    'in_array' => 'يجب أن يكون حقل :attribute موجودًا في :other.',
    'in_array_keys' => 'يجب أن يحتوي حقل :attribute على مفتاح واحد على الأقل من المفاتيح التالية: :values.',
    'integer' => 'يجب أن يكون حقل :attribute رقمًا صحيحًا.',
    'ip' => 'يجب أن يكون حقل :attribute عنوان IP صالحًا.',
    'ipv4' => 'يجب أن يكون حقل :attribute عنوان IPv4 صالحًا.',
    'ipv6' => 'يجب أن يكون حقل :attribute عنوان IPv6 صالحًا.',
    'json' => 'يجب أن يكون حقل :attribute نص JSON صالحًا.',
    'list' => 'يجب أن يكون حقل :attribute قائمة.',
    'lowercase' => 'يجب أن يكون حقل :attribute بأحرف صغيرة.',
    'lt' => [
        'array' => 'يجب أن يحتوي حقل :attribute على أقل من :value عنصرًا.',
        'file' => 'يجب أن يكون حجم حقل :attribute أقل من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من :value.',
        'string' => 'يجب أن يكون طول حقل :attribute أقل من :value حرفًا.',
    ],
    'lte' => [
        'array' => 'يجب ألا يحتوي حقل :attribute على أكثر من :value عنصرًا.',
        'file' => 'يجب أن يكون حجم حقل :attribute أقل من أو يساوي :value كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute أقل من أو تساوي :value.',
        'string' => 'يجب أن يكون طول حقل :attribute أقل من أو يساوي :value حرفًا.',
    ],
    'mac_address' => 'يجب أن يكون حقل :attribute عنوان MAC صالحًا.',
    'max' => [
        'array' => 'يجب ألا يحتوي حقل :attribute على أكثر من :max عنصرًا.',
        'file' => 'يجب ألا يكون حجم حقل :attribute أكبر من :max كيلوبايت.',
        'numeric' => 'يجب ألا تكون قيمة حقل :attribute أكبر من :max.',
        'string' => 'يجب ألا يتجاوز طول حقل :attribute :max حرفًا.',
    ],
    'max_digits' => 'يجب ألا يتكون حقل :attribute من أكثر من :max أرقام.',
    'mimes' => 'يجب أن يكون حقل :attribute ملفًا من نوع: :values.',
    'mimetypes' => 'يجب أن يكون حقل :attribute ملفًا من نوع: :values.',
    'min' => [
        'array' => 'يجب أن يحتوي حقل :attribute على الأقل :min عنصرًا.',
        'file' => 'يجب أن يكون حجم حقل :attribute على الأقل :min كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute على الأقل :min.',
        'string' => 'يجب ألا يقل طول حقل :attribute عن :min أحرف.',
    ],
    'min_digits' => 'يجب أن يتكون حقل :attribute من على الأقل :min أرقام.',
    'missing' => 'يجب أن يكون حقل :attribute مفقودًا.',
    'missing_if' => 'يجب أن يكون حقل :attribute مفقودًا عندما يكون :other هو :value.',
    'missing_unless' => 'يجب أن يكون حقل :attribute مفقودًا ما لم يكن :other هو :value.',
    'missing_with' => 'يجب أن يكون حقل :attribute مفقودًا عندما يكون :values موجودًا.',
    'missing_with_all' => 'يجب أن يكون حقل :attribute مفقودًا عندما تكون جميع :values موجودة.',
    'multiple_of' => 'يجب أن تكون قيمة حقل :attribute من مضاعفات :value.',
    'not_in' => 'القيمة المختارة لـ :attribute غير صالحة.',
    'not_regex' => 'صيغة حقل :attribute غير صالحة.',
    'numeric' => 'يجب أن يكون حقل :attribute رقمًا.',
    'password' => [
        'letters' => 'يجب أن يحتوي حقل :attribute على حرف واحد على الأقل.',
        'mixed' => 'يجب أن يحتوي حقل :attribute على حرف كبير وحرف صغير واحد على الأقل.',
        'numbers' => 'يجب أن يحتوي حقل :attribute على رقم واحد على الأقل.',
        'symbols' => 'يجب أن يحتوي حقل :attribute على رمز واحد على الأقل.',
        'uncompromised' => 'لقد ظهر :attribute المُعطى في تسريب بيانات. يرجى اختيار :attribute مختلف.',
    ],
    'present' => 'يجب أن يكون حقل :attribute موجودًا.',
    'present_if' => 'يجب أن يكون حقل :attribute موجودًا عندما يكون :other هو :value.',
    'present_unless' => 'يجب أن يكون حقل :attribute موجودًا ما لم يكن :other هو :value.',
    'present_with' => 'يجب أن يكون حقل :attribute موجودًا عندما يكون :values موجودًا.',
    'present_with_all' => 'يجب أن يكون حقل :attribute موجودًا عندما تكون جميع :values موجودة.',
    'prohibited' => 'حقل :attribute ممنوع.',
    'prohibited_if' => 'حقل :attribute ممنوع عندما يكون :other هو :value.',
    'prohibited_if_accepted' => 'حقل :attribute ممنوع عندما يكون :other مقبولًا.',
    'prohibited_if_declined' => 'حقل :attribute ممنوع عندما يكون :other مرفوضًا.',
    'prohibited_unless' => 'حقل :attribute ممنوع ما لم يكن :other في :values.',
    'prohibits' => 'حقل :attribute يمنع وجود :other.',
    'regex' => 'صيغة حقل :attribute غير صالحة.',
    'required' => 'حقل :attribute مطلوب.',
    'required_array_keys' => 'يجب أن يحتوي حقل :attribute على مدخلات لـ: :values.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
    'required_if_accepted' => 'حقل :attribute مطلوب عندما يكون :other مقبولًا.',
    'required_if_declined' => 'حقل :attribute مطلوب عندما يكون :other مرفوضًا.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عندما يكون :values موجودًا.',
    'required_with_all' => 'حقل :attribute مطلوب عندما تكون جميع :values موجودة.',
    'required_without' => 'حقل :attribute مطلوب عندما لا يكون :values موجودًا.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا تكون أي من :values موجودة.',
    'same' => 'يجب أن يتطابق حقل :attribute مع :other.',
    'size' => [
        'array' => 'يجب أن يحتوي حقل :attribute على :size عنصرًا.',
        'file' => 'يجب أن يكون حجم حقل :attribute :size كيلوبايت.',
        'numeric' => 'يجب أن تكون قيمة حقل :attribute :size.',
        'string' => 'يجب أن يكون طول حقل :attribute :size حرفًا.',
    ],
    'starts_with' => 'يجب أن يبدأ حقل :attribute بأحد مما يلي: :values.',
    'string' => 'يجب أن يكون حقل :attribute نصًا.',
    'timezone' => 'يجب أن يكون حقل :attribute منطقة زمنية صالحة.',
    'unique' => 'تم استخدام :attribute بالفعل.',
    'uploaded' => 'فشل تحميل حقل :attribute.',
    'uppercase' => 'يجب أن يكون حقل :attribute بأحرف كبيرة.',
    'url' => 'يجب أن يكون حقل :attribute رابطًا صالحًا.',
    'ulid' => 'يجب أن يكون حقل :attribute ULID صالحًا.',
    'uuid' => 'يجب أن يكون حقل :attribute UUID صالحًا.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // === عام ===
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'whatsapp' => 'رقم واتساب',
        'address' => 'العنوان',
        'city' => 'المدينة',
        'postal_code' => 'الرمز البريدي',
        'description' => 'الوصف',
        'image' => 'الصورة',
        'image_path' => 'الصورة',
        'video' => 'الفيديو',
        'slug' => 'الرابط الثابت',
        'sort_order' => 'ترتيب الظهور',
        'is_available' => 'حالة التوفر',
        'is_featured' => 'مميز',
        'is_active' => 'مفعّل',
        'icon' => 'الأيقونة',

        // === المستخدمون ===
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'role' => 'الدور',

        // === إعدادات المطعم ===
        'google_maps_link' => 'رابط خرائط جوجل',
        'accepts_reservations' => 'استقبال الحجوزات',
        'accepts_online_orders' => 'استقبال الطلبات الإلكترونية',
        'opening_hours' => 'ساعات العمل',
        'vat_rate' => 'نسبة الضريبة',
        'max_quantity_per_item' => 'الحد الأقصى للكمية لكل طبق',

        // === أصناف القائمة (Menu Items) ===
        'menu_category_id' => 'تصنيف القائمة',
        'price' => 'السعر',
        'is_spicy' => 'طبق حار',
        'allergens' => 'مسببات الحساسية',

        // === مناطق التوصيل ===
        'region_name' => 'اسم المنطقة',
        'delivery_cost' => 'تكلفة التوصيل',

        // === العملاء ===
        'customer_id' => 'العميل المسجل',

        // === الحجوزات ===
        'customer_name' => 'اسم العميل',
        'customer_phone' => 'رقم هاتف العميل',
        'customer_email' => 'البريد الإلكتروني للعميل',
        'reservation_date' => 'تاريخ الحجز',
        'reservation_time' => 'وقت الحجز',
        'party_size' => 'عدد الأشخاص',
        'special_requests' => 'الطلبات الخاصة',
        'reference_code' => 'رقم المرجع',

        // === الطلبات ===
        'order_number' => 'رقم الطلب المرجعي',
        'menu_item_id' => 'الطبق',
        'order_type' => 'نوع الخدمة',
        'type' => 'نوع الطلب',
        'quantity' => 'الكمية',
        'unit_price' => 'سعر الوحدة',
        'total_price' => 'الإجمالي',
        'delivery_address' => 'عنوان التوصيل',
        'delivery_city' => 'مدينة التوصيل',
        'delivery_postal_code' => 'الرمز البريدي للتوصيل',
        'notes' => 'الملاحظات',
        'status' => 'الحالة',
        'payment_status' => 'حالة الدفع',
        'payment_method' => 'طريقة الدفع',
        'subtotal' => 'المجموع',
        'tax' => 'الضريبة',
        'total' => 'الإجمالي النهائي',

        // === معرض الصور ===
        'thumbnail_path' => 'مسار الصورة المصغرة',
        'category' => 'القسم',
        'views_count' => 'عدد المشاهدات',

        // === الكوبونات ===
        'code' => 'كود الكوبون',
        'discount_type' => 'نوع الخصم',
        'discount_value' => 'قيمة الخصم',
        'applies_to' => 'نطاق التطبيق',
        'menuItems' => 'المنتجات المشمولة',
        'menuCategories' => 'التصنيفات المشمولة',
        'expires_at' => 'تاريخ انتهاء الصلاحية',
        'min_order_total' => 'الحد الأدنى للطلب',
        'max_uses' => 'الحد الأقصى للاستخدام الكلي',
        'max_uses_per_customer' => 'الحد الأقصى لكل عميل',
        'used_count' => 'عدد مرات الاستخدام',

        // === العروض الترويجية ===
        'coupon_id' => 'الكوبون المرتبط',
        'starts_at' => 'تاريخ البداية',
        'ends_at' => 'تاريخ النهاية',

        // === نموذج التواصل ===
        'subject' => 'موضوع الرسالة',
        'message' => 'نص الرسالة',

        // === الحقول المترجمة (AR) ===
        'name.ar' => 'الاسم بالعربية',
        'name.en' => 'الاسم بالإنجليزية',
        'name.nl' => 'الاسم بالهولندية',
        'description.ar' => 'الوصف بالعربية',
        'description.en' => 'الوصف بالإنجليزية',
        'description.nl' => 'الوصف بالهولندية',
        'title.ar' => 'العنوان بالعربية',
        'title.en' => 'العنوان بالإنجليزية',
        'title.nl' => 'العنوان بالهولندية',
        'subtitle.ar' => 'العنوان الفرعي بالعربية',
        'subtitle.en' => 'العنوان الفرعي بالإنجليزية',
        'subtitle.nl' => 'العنوان الفرعي بالهولندية',
        'eyebrow.ar' => 'النص العلوي بالعربية',
        'eyebrow.en' => 'النص العلوي بالإنجليزية',
        'eyebrow.nl' => 'النص العلوي بالهولندية',
        'badge.ar' => 'الشارة المميزة بالعربية',
        'badge.en' => 'الشارة المميزة بالإنجليزية',
        'badge.nl' => 'الشارة المميزة بالهولندية',
        'alt_text.ar' => 'النص البديل بالعربية',
        'alt_text.en' => 'النص البديل بالإنجليزية',
        'alt_text.nl' => 'النص البديل بالهولندية',
        'cta_text.ar' => 'نص الزر بالعربية',
        'cta_text.en' => 'نص الزر بالإنجليزية',
        'cta_text.nl' => 'نص الزر بالهولندية',

        // داخل مصفوفة 'attributes' أضف:
        'items' => 'عناصر الطلب',
        'items.*.menu_item_id' => 'الطبق',
        'items.*.type' => 'نوع الطلب',
        'items.*.quantity' => 'الكمية',
        'items.*.unit_price' => 'سعر الوحدة',
        'items.*.total_price' => 'الإجمالي',
        'items.*.delivery_address' => 'عنوان التوصيل',
        'items.*.delivery_city' => 'مدينة التوصيل',
        'items.*.delivery_postal_code' => 'الرمز البريدي للتوصيل',
    ],

];
