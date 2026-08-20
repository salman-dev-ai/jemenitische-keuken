<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use App\Models\MenuCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use OpenSpout\Common\Entity\Style\Border;

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

                                        TextInput::make('name.ar')
                                            ->label('اسم الطبق بالعربية')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),

                                        TextInput::make('name.en')
                                            ->label('اسم الطبق بالإنجليزية')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('name.nl')
                                            ->label('اسم الطبق بالهولندية')
                                            ->required()
                                            ->maxLength(255),

                                    ])->columnSpanFull(),

                                /*
                                        |--------------------------------------------------------------------------
                                        | Slug
                                        |--------------------------------------------------------------------------
                                        */

                                TextInput::make('slug')
                                    ->label('الرابط الثابت')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText(
                                        'يستخدم لإنشاء رابط فريد للطبق.'
                                    ),

                                /*
                                        |--------------------------------------------------------------------------
                                        | الوصف
                                        |--------------------------------------------------------------------------
                                        */

                                Grid::make(3)
                                    ->schema([

                                        Textarea::make('description.ar')
                                            ->label('الوصف بالعربية')
                                            ->rows(2),

                                        Textarea::make('description.en')
                                            ->label('الوصف بالإنجليزية')
                                            ->rows(2),

                                        Textarea::make('description.nl')
                                            ->label('الوصف بالهولندية')
                                            ->rows(2),

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

                        // Select::make('menu_category_id')
                        //     ->label('قسم المنيو')
                        //     ->relationship(
                        //         'category',
                        //         'name'
                        //     )
                        //     ->required()
                        //     ->searchable()
                        //     ->preload(),

                        Select::make('menu_category_id')
                            ->label('تصنيف القائمة')
                            ->options(
                               MenuCategory::query()
                                    ->get()
                                    ->mapWithKeys(function ($category) {
                                        return [
                                            $category->id => $category->localized_name,

                                            
                                        ];
                                    })
                                    ->toArray()
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
