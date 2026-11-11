<?php

declare(strict_types=1);

namespace App\Filament\Resources\CookieConsents;

use App\Filament\Resources\CookieConsents\Pages\ListCookieConsents;
use App\Filament\Resources\CookieConsents\Tables\CookieConsentsTable;
use App\Models\CookieConsent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * ═══════════════════════════════════════════════════════════════════════════
 * 📄 المسار: app/Filament/Resources/CookieConsents/CookieConsentResource.php
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * 🎯 الغرض:
 *    عرض سجل الموافقات على الكوكيز (إثبات الموافقة — GDPR) للقراءة فقط.
 *    لا إنشاء ولا تعديل ولا حذف من اللوحة.
 *
 * 🕒 آخر تحديث: 2026-11-10
 * ═══════════════════════════════════════════════════════════════════════════
 */
class CookieConsentResource extends Resource
{
    protected static ?string $model = CookieConsent::class;

    protected static string|BackedEnum|null $navigationIcon = 'lucide-shield-check';

    protected static ?string $navigationLabel = 'موافقات الكوكيز';

    protected static ?string $modelLabel = 'موافقة كوكيز';

    protected static ?string $pluralModelLabel = 'سجل موافقات الكوكيز';

    protected static string|UnitEnum|null $navigationGroup = 'الخصوصية';

    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return CookieConsentsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'gray';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCookieConsents::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
