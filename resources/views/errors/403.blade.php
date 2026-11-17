@include('errors.layout', [
    'code'      => 403,
    'actions'   => ['home', 'back'],
    'autoRetry' => null,
    'copy'      => [
        'nl' => ['title' => 'Deze tafel is gereserveerd', 'text' => 'Je hebt geen toegang tot deze pagina. Denk je dat dit niet klopt? Neem dan contact met ons op.'],
        'en' => ['title' => 'This table is reserved', 'text' => 'You don\'t have permission to view this page. If you think this is a mistake, please contact us.'],
        'ar' => ['title' => 'هذه الطاولة محجوزة', 'text' => 'ليس لديك صلاحية لعرض هذه الصفحة. إذا كنت تعتقد أن هذا خطأ فتواصل معنا.'],
    ],
])
