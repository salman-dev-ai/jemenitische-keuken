@include('errors.layout', [
    'code'      => 404,
    'actions'   => ['home', 'back'],
    'livewire'  => true,
    'autoRetry' => null,
    'copy'      => [
        'nl' => ['title' => 'Deze pagina staat niet op het menu', 'text' => 'De pagina die je zoekt bestaat niet of is verplaatst. Ga terug naar de startpagina of controleer het adres.'],
        'en' => ['title' => 'This page isn\'t on the menu', 'text' => 'The page you\'re looking for doesn\'t exist or has moved. Head back to the homepage or check the address.'],
        'ar' => ['title' => 'هذه الصفحة غير موجودة في القائمة', 'text' => 'الصفحة التي تبحث عنها غير موجودة أو تم نقلها. عد إلى الصفحة الرئيسية أو تحقق من العنوان.'],
    ],
])
