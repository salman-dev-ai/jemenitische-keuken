<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;


class GalleryItemForm
{
    // public static function configure(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             TextInput::make('category')
    //                 ->required()
    //                 ->default('mandi'),
    //             Textarea::make('title')
    //                 ->required()
    //                 ->columnSpanFull(),
    //             Textarea::make('description')
    //                 ->columnSpanFull(),
    //             Textarea::make('badge')
    //                 ->columnSpanFull(),
    //             FileUpload::make('image_path')
    //                 ->image()
    //                 ->required(),

    //             TextInput::make('thumbnail_path'),
    //             Textarea::make('alt_text')
    //                 ->columnSpanFull(),
    //             TextInput::make('sort_order')
    //                 ->required()
    //                 ->numeric()
    //                 ->default(0),
    //             Toggle::make('is_featured')
    //                 ->required(),
    //             Toggle::make('is_active')
    //                 ->required(),
    //             TextInput::make('views_count')
    //                 ->required()
    //                 ->numeric()
    //                 ->default(0),
    //         ]);
    // }



    public static function configure(Schema $schema): Schema
{
    return $schema
        ->components([
            // =========================================================================
            // 1. تبويبات النصوص والترجمة باللغات الثلاث (عربي - إنجليزي - هولندي)
            // =========================================================================
            Tabs::make('معلومات الصورة والترجمة')
                ->tabs([
                    // ------------------ التبويب العربي (الأساسي) ------------------
                    Tab::make('🇸🇦 اللغة العربية (الأساسية)')
                        ->icon('heroicon-m-language')
                        ->schema([
                            TextInput::make('title.ar')
                                ->label('عنوان الصورة (بالعربي) *')
                                ->placeholder('مثال: وليمة مندي اللحم الملكي بالأرز العنبر')
                                ->required()
                                ->maxLength(150),

                            Textarea::make('description.ar')
                                ->label('وصف الطبق / الجلسة (بالعربي) *')
                                ->placeholder('مثال: لحم ضأن طازج مطهو في برميل الحطب التقليدي لأكثر من 4 ساعات مع البصل المحمر والمكسرات الذهبية...')
                                ->rows(3)
                                ->required()
                                ->columnSpanFull(),

                            Grid::make(2)->schema([
                                TextInput::make('badge.ar')
                                    ->label('الشارة المميزة (بالعربي)')
                                    ->placeholder('مثال: سيد المائدة الملكية 👑')
                                    ->helperText('شارة صغيرة تظهر أعلى الصورة في الموقع.'),

                                TextInput::make('alt_text.ar')
                                    ->label('النص البديل لتحسين محركات البحث SEO (بالعربي)')
                                    ->placeholder('مثال: مندي لحم يمني أصيل في أمستردام'),
                            ]),
                        ]),

                    // ------------------ التبويب الإنجليزي ------------------
                    Tab::make('🇬🇧 English')
                        ->schema([
                            TextInput::make('title.en')
                                ->label('Image Title (English)')
                                ->placeholder('e.g., Royal Lamb Mandi Feast'),

                            Textarea::make('description.en')
                                ->label('Description (English)')
                                ->placeholder('e.g., Slow wood-pit cooked fresh lamb with golden fried onions, toasted almonds, and raisins.')
                                ->rows(3)
                                ->columnSpanFull(),

                            Grid::make(2)->schema([
                                TextInput::make('badge.en')
                                    ->label('Badge (English)')
                                    ->placeholder('e.g., Royal Feast Master 👑'),

                                TextInput::make('alt_text.en')
                                    ->label('Alt Text SEO (English)')
                                    ->placeholder('e.g., Authentic Yemeni Lamb Mandi in Amsterdam'),
                            ]),
                        ]),

                    // ------------------ التبويب الهولندي ------------------
                    Tab::make('🇳🇱 Nederlands')
                        ->schema([
                            TextInput::make('title.nl')
                                ->label('Titel (Nederlands)')
                                ->placeholder('bijv. Koninklijke Lams Mandi met Saffraanrijst'),

                            Textarea::make('description.nl')
                                ->label('Beschrijving (Nederlands)')
                                ->placeholder('bijv. Langzaam in houtkuil gegaard lamsvlees met gebakken uitjes, amandelen en rozijnen.')
                                ->rows(3)
                                ->columnSpanFull(),

                            Grid::make(2)->schema([
                                TextInput::make('badge.nl')
                                    ->label('Badge (Nederlands)')
                                    ->placeholder('bijv. Koninklijk Meesterwerk 👑'),

                                TextInput::make('alt_text.nl')
                                    ->label('Alt Tekst SEO (Nederlands)')
                                    ->placeholder('bijv. Traditionele Jemenitische Mandi in Amsterdam'),
                            ]),
                        ]),
                ])
                ->columnSpanFull(),

            // =========================================================================
            // 2. قسم رفع الصورة والوسائط
            // =========================================================================
            Section::make('ملف الصورة والوسائط')
                ->description('ارفع صورة عالية الدقة تعكس فخامة الأطباق والجلسات التراثية')
                ->schema([
                    FileUpload::make('image_path')
                        ->label('صورة المعرض بجودة عالية *')
                        ->image()
                        // ->directory('gallery')
                        // ->disk('public')
                        // ->imageEditor()
                        ->required()
                        ->helperText('يفضل استخدام صور أفقية بدقة لا تقل عن 1200x800 بكسل.'),

                    TextInput::make('thumbnail_path')
                        ->label('مسار الصورة المصغرة (اختياري)')
                        ->placeholder('يتم إنشاؤه تلقائياً في حال تركه فارغاً')
                        ->helperText('يمكن تركه فارغاً للتحميل التلقائي.'),
                ])
                ->columns(2),

            // =========================================================================
            // 3. قسم التصنيف والإعدادات العامة
            // =========================================================================
            Section::make('التصنيف وإعدادات النشر')
                ->schema([
                    Select::make('category')
                        ->label('القسم التراثي *')
                        ->options([
                            'mandi'   => '👑 المندي والمظبي الملكي',
                            'pots'    => '🔥 الفخاريات والمقلى الصنعاني الساخن',
                            'majlis'  => '🛋️ الديوان والجلسات التراثية العائلية',
                            'coffee'  => '☕ الضيافة والشاي والحلويات والعسل الدوعني',
                            'bread'   => '🫓 المخبوزات والملوح في التنور',
                        ])
                        ->default('mandi')
                        ->required()
                        ->native(false)
                        ->helperText('يحدد التبويب الذي ستظهر فيه الصورة في صفحة المعرض.'),

                    TextInput::make('sort_order')
                        ->label('ترتيب الظهور في الموقع')
                        ->numeric()
                        ->default(0)
                        ->helperText('الأرقام الأصغر تظهر أولاً (مثال: 1 يظهر قبل 2).'),

                    TextInput::make('views_count')
                        ->label('عدد المشاهدات التراكمي')
                        ->numeric()
                        ->default(0)
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('يتم احتسابه تلقائياً عند فتح الزوار للصورة في الموقع.'),

                    Toggle::make('is_active')
                        ->label('تفعيل الصورة (تظهر في الموقع مباشرة)')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger')
                        ->helperText('عند التعطيل لن يتمكن زوار الموقع من رؤية الصورة.'),

                    Toggle::make('is_featured')
                        ->label('صورة مميزة في الصفحة الرئيسية ⭐')
                        ->default(false)
                        ->onColor('warning')
                        ->helperText('تظهر هذه الصورة في الأماكن المميزة من الصفحة الرئيسية.'),
                ])
                ->columns(2),
        ]);


}
}
