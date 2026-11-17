@include('errors.layout', [
    'code'      => 401,
    'actions'   => ['login', 'home'],
    'autoRetry' => null,
    'copy'      => [
        'nl' => ['title' => 'Log eerst in', 'text' => 'Voor deze pagina moet je ingelogd zijn.'],
        'en' => ['title' => 'Sign in first', 'text' => 'You need to be signed in to view this page.'],
        'ar' => ['title' => 'سجّل الدخول أولاً', 'text' => 'يجب تسجيل الدخول لعرض هذه الصفحة.'],
    ],
])
