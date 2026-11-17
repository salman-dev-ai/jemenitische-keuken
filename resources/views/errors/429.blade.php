@include('errors.layout', [
    'code'      => 429,
    'actions'   => ['retry', 'home'],
    'autoRetry' => 60,
    'copy'      => [
        'nl' => ['title' => 'Even geduld, de keuken is druk', 'text' => 'Je hebt te veel verzoeken gedaan. Wacht even, we proberen het automatisch opnieuw.'],
        'en' => ['title' => 'Hang on, the kitchen is busy', 'text' => 'You\'ve sent too many requests. Please wait a moment, we\'ll retry automatically.'],
        'ar' => ['title' => 'لحظة من فضلك، المطبخ مزدحم', 'text' => 'أرسلت طلبات كثيرة. انتظر قليلاً وسنعيد المحاولة تلقائيًا.'],
    ],
])
