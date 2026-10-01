<?php

namespace App\Filament\Resources\DeliveryZones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeliveryZonesTable
{
    public static function configure(Table $table): Table
    {
            return $table
            ->columns([
                TextColumn::make('region_name')      // عمود اسم المنطقة
                    ->label('اسم المنطقة')
                    ->searchable()                   // قابل للبحث
                    ->sortable(),                    // قابل للترتيب

                TextColumn::make('postal_code')      // عمود الرمز البريدي
                    ->label('الرمز البريدي')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('delivery_cost')    // عمود تكلفة التوصيل
                    ->label('تكلفة التوصيل')
                    ->money('EUR')                   // عرض القيمة كعملة اليورو
                    ->sortable(),

                TextColumn::make('created_at')       // عمود تاريخ الإنشاء
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')          // تنسيق التاريخ والوقت
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // مخفي افتراضيًا

                TextColumn::make('updated_at')       // عمود تاريخ التحديث
                    ->label('تاريخ التحديث')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // يمكن إضافة فلاتر هنا لاحقًا (مثل فلتر حسب نطاق الرمز البريدي).
            ])
            ->recordActions([                        // إجراءات كل صف
                EditAction::make(),                  // زر التعديل
                DeleteAction::make(),                // زر الحذف مع تأكيد
            ])
            ->toolbarActions([                       // إجراءات الشريط العلوي
                BulkActionGroup::make([
                    DeleteBulkAction::make(),        // حذف جماعي للسجلات المحددة
                ]),
            ]);
    }

}
