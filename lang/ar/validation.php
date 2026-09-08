<?php

return [
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'يجب أن يكون :attribute بريدًا إلكترونيًا صحيحًا.',
    'url' => 'يجب أن يكون :attribute رابطًا صحيحًا.',
    'max' => [
        'string' => 'يجب ألا يتجاوز :attribute :max حرفًا.',
    ],
    'min' => [
        'string' => 'يجب ألا يقل :attribute عن :min أحرف.',
    ],

    'unique' => '  :attribute مستخدم بالفعل . ',
    'date' => 'يجب أن يكون :attribute تاريخًا صحيحًا.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخًا لاحقًا أو مطابقًا لـ :date.',
    'integer' => 'يجب أن يكون :attribute رقمًا صحيحًا.',

    'attributes' => [
        'customer_name' => 'الاسم',
        'customer_phone' => 'رقم الهاتف',
        'customer_email' => 'البريد الإلكتروني',
        'reservation_date' => 'تاريخ الحجز',
        'reservation_time' => 'وقت الحضور',
        'party_size' => 'عدد الضيوف',
        'special_requests' => 'الطلبات الخاصة',
        'order_type' => 'نوع الخدمة',
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'subject' => 'موضوع الرسالة',
        'message' => 'نص الرسالة',
    ],
];
