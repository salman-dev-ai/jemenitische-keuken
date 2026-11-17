@include('errors.layout', [
    'code'      => 419,
    'actions'   => ['retry', 'home'],
    'autoRetry' => null,
    'copy'      => [
        'nl' => ['title' => 'Je sessie is verlopen', 'text' => 'Om veiligheidsredenen is je sessie afgelopen. Vernieuw de pagina en probeer het opnieuw.'],
        'en' => ['title' => 'Your session has expired', 'text' => 'For security, your session ended. Refresh the page and try again.'],
        'ar' => ['title' => 'انتهت جلستك', 'text' => 'لأسباب أمنية انتهت جلستك. حدّث الصفحة وحاول مرة أخرى.'],
    ],
])
