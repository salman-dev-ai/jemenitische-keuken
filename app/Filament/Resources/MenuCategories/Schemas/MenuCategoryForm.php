<?php

namespace App\Filament\Resources\MenuCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class MenuCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('ترجمات القسم')
                    ->tabs([
                        Tab::make('🇸🇦 العربية')
                            ->icon('heroicon-m-language')
                            ->schema([
                                TextInput::make('name.ar')
                                    ->label('اسم القسم بالعربية')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true),

                                Textarea::make('description.ar')
                                    ->label('الوصف بالعربية')
                                    ->rows(3),
                            ]),

                        Tab::make('🇬🇧 English')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Category Name (English)')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description.en')
                                    ->label('Description (English)')
                                    ->rows(3),
                            ]),

                        Tab::make('🇳🇱 Nederlands')
                            ->schema([
                                TextInput::make('name.nl')
                                    ->label('Categorienaam (Nederlands)')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description.nl')
                                    ->label('Beschrijving (Nederlands)')
                                    ->rows(3),
                            ]),
                    ])
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->label('الرابط الثابت (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('يُستخدم في روابط الموقع، يتم توليده تلقائياً.'),

                Grid::make(2)->schema([
                    FileUpload::make('image_path')
                        ->image()
                        ->label('صورة القسم')
                        ->directory('menu-categories')
                        ->disk('public')
                        ->imageEditor(),

                    Grid::make(2)->schema([
                        TextInput::make('sort_order')
                            ->label('ترتيب الفرز')
                            ->required()
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_available')
                            ->label('القسم مفعّل')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(true),
                    ]),
                ]),
            ]);
    }
}
