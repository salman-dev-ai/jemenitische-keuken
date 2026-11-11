<?php

declare(strict_types=1);

namespace App\Filament\Resources\CookieConsents\Pages;

use App\Filament\Resources\CookieConsents\CookieConsentResource;
use Filament\Resources\Pages\ListRecords;

/**
 * صفحة سجل الموافقات — للقراءة فقط (لا إجراءات إنشاء).
 */
class ListCookieConsents extends ListRecords
{
    protected static string $resource = CookieConsentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
