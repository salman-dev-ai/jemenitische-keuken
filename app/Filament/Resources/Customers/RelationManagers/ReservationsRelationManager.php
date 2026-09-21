<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Enums\ReservationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationsRelationManager extends RelationManager
{
    protected static string $relationship = 'reservations';

    protected static ?string $title = 'الحجوزات';

    protected static ?string $modelLabel = 'حجز';

    protected static ?string $pluralModelLabel = 'الحجوزات';

    // 🔧 التصحيح 1: استخدام Schema بدل Form في التوقيع
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('customer_name')
                    ->label('اسم العميل')
                    ->required()
                    ->maxLength(255),

                TextInput::make('customer_phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                TextInput::make('customer_email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->maxLength(255),

                TextInput::make('party_size')
                    ->label('عدد الأشخاص')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(20),

                DatePicker::make('reservation_date')
                    ->label('تاريخ الحجز')
                    ->required()
                    ->native(false)
                    ->displayFormat('Y-m-d'),

                TimePicker::make('reservation_time')
                    ->label('وقت الحجز')
                    ->required()
                    ->seconds(false),

                Select::make('status')
                    ->label('حالة الحجز')
                    ->options(ReservationStatus::class)
                    ->required()
                    ->default(ReservationStatus::PENDING),

                Textarea::make('special_requests')
                    ->label('طلبات خاصة')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference_code')
            ->columns([
                TextColumn::make('reference_code')
                    ->label('المرجع')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('reservation_date')
                    ->label('التاريخ')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('reservation_time')
                    ->label('الوقت')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('party_size')
                    ->label('الأشخاص')
                    ->numeric()
                    ->badge(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('تصفية حسب الحالة')
                    ->options(ReservationStatus::class),
            ])
            ->headerActions([
                // 🔧 التصحيح 2: حذف AttachAction (hasMany لا تدعم Attach/Detach)
                CreateAction::make()
                    ->label('إضافة حجز'),
            ])
            ->recordActions([
                EditAction::make(),
                // 🔧 التصحيح 2: حذف DetachAction (hasMany لا تدعم Attach/Detach)
                DeleteAction::make()
                    ->label('حذف'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // 🔧 التصحيح 2: حذف DetachBulkAction
                    DeleteBulkAction::make()
                        ->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}