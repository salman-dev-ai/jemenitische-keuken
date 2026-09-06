<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('بيانات المستخدم')
                    ->description('المعلومات الأساسية للمستخدم')
                    ->icon('heroicon-o-user')
                    ->schema([

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([

                                TextInput::make('name')
                                    ->label('الاسم')
                                    ->placeholder('أدخل اسم المستخدم')
                                    ->required()
                                    ->minLength(3)
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->placeholder('example@email.com')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),

                            ]),

                    ])
                    ->columnSpanFull(),

                Section::make('الأمان والصلاحيات')
                    ->description('كلمة المرور والدور الوظيفي للمستخدم')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])
                            ->schema([

                                TextInput::make('password')
                                    ->label('كلمة المرور')
                                    ->password()
                                    ->revealable()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->minLength(8)
                                    ->confirmed()
                                    ->dehydrateStateUsing(
                                        fn (?string $state): ?string =>
                                            filled($state) ? $state : null
                                    )
                                    ->dehydrated(
                                        fn (?string $state): bool =>
                                            filled($state)
                                    ),

                                TextInput::make('password_confirmation')
                                    ->label('تأكيد كلمة المرور')
                                    ->password()
                                    ->revealable()
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->dehydrated(false),

                                Select::make('role')
                                    ->label('الدور')
                                    ->placeholder('اختر دور المستخدم')
                                    ->options([
                                        'super_admin' => 'Super Admin',
                                        'admin' => 'Admin',
                                        'content_manager' => 'Content Manager',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->searchable(),

                            ]),

                    ])
                    ->columnSpanFull(),

            ]);
    }
}
