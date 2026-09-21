<?php

namespace App\Filament\Resources\OrderOptions\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class OrderOptionForm
{
    public static function configure(Schema $schema): Schema
    {
          return $schema
            ->components([
                // ========== العمود الرئيسي (2/3) ==========
                Section::make('محتوى الخيار')
                    ->description('أدخل بيانات الخيار بجميع اللغات')
                    ->icon("heroicon-o-document-text")
                    ->schema([
                        Tabs::make('translations')
                            ->tabs([
                                // ----- العربية -----
                                Tab::make('العربية (AR)')
                                    ->icon("lucide-languages")
                                    ->schema([
                                        TextInput::make('title.ar')
                                            ->label('العنوان')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('مثال: توصيل سريع'),

                                        Textarea::make('description.ar')
                                            ->label('الوصف')
                                            ->rows(3)
                                            ->maxLength(500)
                                            ->placeholder('وصف مختصر للخيار'),
                                    ]),

                                // ----- الهولندية -----
                                Tab::make('الهولندية (NL)')
                                    ->icon("heroicon-o-language")
                                    ->schema([
                                        TextInput::make('title.nl')
                                            ->label('Titel')
                                            ->maxLength(255),

                                        Textarea::make('description.nl')
                                            ->label('Beschrijving')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),

                                // ----- الإنجليزية -----
                                Tab::make('الإنجليزية (EN)')
                                    ->icon("heroicon-o-language")
                                    ->schema([
                                        TextInput::make('title.en')
                                            ->label('Title')
                                            ->maxLength(255),

                                        Textarea::make('description.en')
                                            ->label('Description')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                            ])
                            ->activeTab(1)
                            ->persistTabInQueryString(),
                    ])
                    ->columnSpan(['lg' => 2]),

                // ========== الشريط الجانبي (1/3) ==========
                Section::make('الإعدادات والوسائط')
                    ->icon("heroicon-o-cog")
                    ->schema([
                        FileUpload::make('image')
                            ->label('الصورة')
                            ->image()
                            ->imageEditor()
                            ->directory('order-options')
                            ->maxSize(2048)
                            ->required(),

                        Select::make('icon')
                            ->label('الأيقونة')
                            ->options([
                                'truck'         => '🚚 شاحنة توصيل',
                                'shopping-bag'  => '🛍️ كيس تسوق',
                                'utensils'      => '🍽️ أدوات طعام',
                                'clock'         => '⏰ ساعة',
                                'map-pin'       => '📍 موقع',
                                'credit-card'   => '💳 بطاقة دفع',
                                'package'       => '📦 طرد',
                                'store'         => '🏪 متجر',
                            ])
                            ->default('truck')
                            ->required()
                            ->native(false)
                            ->searchable(),

                        Toggle::make('is_active')
                            ->label('مفعل')
                            ->helperText('عند التعطيل لن يظهر في الموقع')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
