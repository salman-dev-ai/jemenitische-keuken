@include('errors.layout', [
    'code'      => 500,
    'actions'   => ['retry', 'home'],
    'autoRetry' => null,
    'copy'      => [
        'nl' => ['title' => 'Er ging iets mis in de keuken', 'text' => 'Er is een fout opgetreden aan onze kant. Probeer het over een paar minuten opnieuw.'],
        'en' => ['title' => 'Something went wrong in the kitchen', 'text' => 'We hit an error on our side. Please try again in a few minutes.'],
        'ar' => ['title' => 'حدث خطأ في المطبخ', 'text' => 'وقع خطأ من جانبنا. حاول مرة أخرى بعد بضع دقائق.'],
    ],
])
