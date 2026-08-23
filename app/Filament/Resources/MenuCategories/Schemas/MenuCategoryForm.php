<?php

namespace App\Filament\Resources\MenuCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم القسم')
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->columnSpanFull()
                    ->required(),

                TextInput::make('slug')
                    ->label('الرابط الثابت (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('يُستخدم في روابط الموقع، يتم توليده تلقائياً.'),

                Textarea::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),

                FileUpload::make('image_path')
                    ->image()
                    ->label('صورة القسم')
                    ->directory('menu-categories')
                    ->disk('public'),

                TextInput::make('sort_order')
                    ->label('ترتيب الفرز')
                    ->required()
                    ->numeric()
                    ->default(0),

                Toggle::make('is_available')
                    ->label('الحالة')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true),
            ]);
    }
}
