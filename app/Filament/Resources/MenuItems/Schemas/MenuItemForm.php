<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use App\Models\MenuCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Set as UtilitiesSet;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make(1)
                    ->schema([

                        /*
                        |--------------------------------------------------------------------------
                        | العمود الرئيسي
                        |--------------------------------------------------------------------------
                        */

                        Section::make('البيانات الأساسية')
                            ->description('اسم الطبق ووصفه باللغات المتاحة')
                            ->icon('heroicon-o-cake')
                            ->schema([

                                Grid::make(3)
                                    ->schema([


                                        Tabs::make('محتوى اللغات')
                                            ->columnSpanFull()
                                            ->tabs([
                                                Tabs\Tab::make('العربية 🇸🇦')
                                                    ->icon('heroicon-o-language')
                                                    ->schema([
                                                        TextInput::make('name.ar')
                                                            ->label('اسم الطبق')
                                                            ->required()
                                                            ->maxLength(255)
                                                         ,

                                                        Textarea::make('description.ar')
                                                            ->label('وصف الطبق')
                                                            ->rows(3)
                                                            ->helperText('اكتب وصفاً جذاباً للطبق باللغة العربية.'),
                                                    ]),

                                                Tabs\Tab::make('English 🇬🇧')
                                                    ->icon('heroicon-o-language')
                                                    ->schema([
                                                        TextInput::make('name.en')
                                                            ->label('Dish Name')
                                                            ->required()
                                                            ->maxLength(255),

                                                        Textarea::make('description.en')
                                                            ->label('Description')
                                                            ->rows(3),
                                                    ]),

                                                Tabs\Tab::make('Nederlands 🇳🇱')
                                                    ->icon('heroicon-o-language')
                                                    ->schema([
                                                        TextInput::make('name.nl')
                                                            ->label('Gerechtnaam')
                                                            ->required()
                                                            ->maxLength(255),

                                                        Textarea::make('description.nl')
                                                            ->label('Beschrijving')
                                                            ->rows(3),
                                                    ]),
                                            ]),
                                    ]),

                                /*
                                        |--------------------------------------------------------------------------
                                        | صورة الطبق
                                        |--------------------------------------------------------------------------
                                        */

                                FileUpload::make('image_path')
                                    ->label('صورة الطبق')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('menu-items')
                                    ->disk('public')
                                    ->maxSize(5120)
                                    ->columnSpanFull(),

                            ])
                            ->columnSpanFull(),

                    ]),

                /*
                                |--------------------------------------------------------------------------
                                | التصنيف والسعر
                                |--------------------------------------------------------------------------
                                */

                Section::make('التصنيف والسعر')
                    ->icon('heroicon-o-tag')
                    ->schema([

                        TextInput::make('slug')
                            ->label('الرابط الثابت')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            // ✅ توليد تلقائي من الاسم العربي
                            ->afterStateUpdated(function (UtilitiesSet $set, ?string $state, ?string $old) {
                                if ($state && $state !== $old) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->helperText('يستخدم لإنشاء رابط فريد للطبق - يُولّد تلقائياً.'),

                        Select::make('menu_category_id')
                            ->label('تصنيف القائمة')
                            ->relationship(
                                name: 'category',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->orderBy('sort_order'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn(MenuCategory $record) => $record->localized_name
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('price')
                            ->label('السعر')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('€')
                            ->helperText(
                                'السعر باليورو (EUR).'
                            ),

                        TextInput::make('sort_order')
                            ->label('ترتيب الطبق')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText(
                                'كلما كان الرقم أصغر ظهر الطبق أولاً.'
                            ),

                    ]),

                /*
                                |--------------------------------------------------------------------------
                                | خصائص الطبق
                                |--------------------------------------------------------------------------
                                */

                Section::make('خصائص الطبق')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([

                        Toggle::make('is_available')
                            ->label('متاح للطلب')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),

                        Toggle::make('is_featured')
                            ->label('طبق مميز ⭐')
                            ->default(false)
                            ->onColor('warning'),

                        Toggle::make('is_spicy')
                            ->label('طبق حار 🌶️')
                            ->default(false)
                            ->onColor('danger'),

                    ])->columns(3),

                /*
                                |--------------------------------------------------------------------------
                                | مسببات الحساسية
                                |--------------------------------------------------------------------------
                                */

                Section::make('مسببات الحساسية')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([

                        TagsInput::make('allergens')
                            ->label('مسببات الحساسية')
                            ->placeholder(
                                'أضف مسبب حساسية'
                            )
                            ->suggestions([
                                'Gluten',
                                'Lactose',
                                'Nuts',
                                'Peanuts',
                                'Eggs',
                                'Soy',
                                'Fish',
                                'Shellfish',
                                'Sesame',
                            ])
                            ->helperText(
                                'مثال: Gluten, Lactose, Nuts'
                            ),

                    ])
                    ->columns(2),

            ]);
    }
}
