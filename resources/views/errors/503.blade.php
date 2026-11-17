@include('errors.layout', [
    'code'      => 503,
    'actions'   => ['retry'],
    'autoRetry' => 30,
    'copy'      => [
        'nl' => ['title' => 'We zijn zo terug', 'text' => 'We werken aan de website. Deze pagina wordt automatisch vernieuwd zodra we klaar zijn.'],
        'en' => ['title' => 'We\'ll be right back', 'text' => 'We\'re doing maintenance. This page refreshes automatically when we\'re done.'],
        'ar' => ['title' => 'سنعود قريبًا', 'text' => 'نقوم بصيانة الموقع. ستتحدث هذه الصفحة تلقائيًا عند الانتهاء.'],
    ],
])
