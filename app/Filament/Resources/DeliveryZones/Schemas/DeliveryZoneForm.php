<?php

namespace App\Filament\Resources\DeliveryZones\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeliveryZoneForm
{
    public static function configure(Schema $schema): Schema
    {
   return $schema
            ->components([
                Section::make('معلومات منطقة التوصيل')   // قسم رئيسي يجمع الحقول
                    ->description('أدخل اسم المنطقة والرمز البريدي وتكلفة التوصيل.')
                    ->schema([
                        TextInput::make('region_name')   // حقل اسم المنطقة
                            ->label('اسم المنطقة')
                            ->required()                 // إلزامي
                            ->maxLength(100)             // الحد الأقصى 100 حرف (مطابق للـ Migration)
                            ->columnSpanFull(),          // يمتد على كامل عرض القسم

                        TextInput::make('postal_code')   // حقل الرمز البريدي
                            ->label('الرمز البريدي')
                            ->required()
                            ->maxLength(20)              // مطابق للـ Migration
                            ->unique(ignoreRecord: true), // فريد، مع تجاهل السجل الحالي عند التعديل
                            // ملاحظة: إذا كنت لا تريد قيد unique، احذف السطر أعلاه.

                        TextInput::make('delivery_cost') // حقل تكلفة التوصيل
                            ->label('تكلفة التوصيل')
                            ->required()
                            ->numeric()                  // يقبل أرقامًا فقط
                            ->prefix('€')                // بادئة عملة اليورو
                            ->minValue(0)                // لا يقبل قيمًا سالبة
                            ->step(0.01),                // خطوة بمقدار هللة واحدة
                    ])
                    ->columns(2),                        // قسم من عمودين
            ]);
    }
}
