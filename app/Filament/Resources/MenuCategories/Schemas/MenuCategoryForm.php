<?php

namespace App\Filament\Resources\MenuCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
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

                Section::make('البيانات الأساسية والوسائط')
                    ->description('إعدادات الرابط الثابت، صورة القسم، وحالة النشر')
                    ->icon('heroicon-o-photo')
                    ->columns(4)
                    ->schema([

                         TextInput::make('slug')
                            ->label('الرابط الثابت (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->alphaDash() // يضمن أن يحتوي فقط على أحرف وأرقام وشرطات
                            ->columnSpan(2) // يأخذ نصف عرض القسم
                            ->helperText('يُستخدم في روابط الموقع، ويتم توليده تلقائياً من الاسم.'),

                        // 2. ترتيب الظهور
                        TextInput::make('sort_order')
                            ->label('ترتيب الظهور')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->columnSpan(1)
                            ->helperText('الأرقام الأصغر تظهر أولاً.'),

                        // 3. حالة التوفر (تم تصحيح المسمى ليبدو احترافياً)
                        Toggle::make('is_available')
                            ->label('حالة القسم')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->columnSpan(1)
                            ->helperText('تفعيل أو إخفاء القسم من الموقع'),

                        // 4. صورة القسم (تأخذ العرض الكامل لتبدو واضحة وجذابة)
                        FileUpload::make('image_path')
                            ->label('صورة القسم')
                            ->image()
                            ->imageEditor()
                            ->directory('menu-categories')
                            ->disk('public')
                            ->columnSpanFull() // تأخذ عرضاً كاملاً لسهولة السحب والإفلات والمعاينة
                    ])->columnSpanFull(),

            ]);
    }
}
