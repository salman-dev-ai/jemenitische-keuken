<?php

declare(strict_types=1);

namespace App\Filament\Resources\Promotions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('promotion_tabs')
                    ->tabs([
                        // ═══ الربط والوسائط ═══
                        Tab::make('الربط والوسائط')
                            ->icon('heroicon-o-link')
                            ->schema([
                                Select::make('coupon_id')
                                    ->label('الكوبون المرتبط')
                                    ->relationship('coupon', 'code')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->helperText('كل عرض مرتبط بكوبون واحد فقط. اختر كوبونًا من القائمة.')
                                    ->getOptionLabelFromRecordUsing(
                                        fn($record): string =>
                                        "{$record->code} — " .
                                            ($record->getTranslation('name', 'ar') ?? '')
                                    ),

                                // ═══ الصورة (إلزامية للبطاقة) ═══
                                FileUpload::make('image')
                                    ->label('صورة البطاقة')
                                    ->image()
                                    ->disk('public')
                                    ->directory('promotions/images')
                                    ->imageEditor()
                                    ->maxSize(5120)                              // 5 MB
                                    ->acceptedFileTypes([
                                        'image/jpeg',
                                        'image/png',
                                        'image/webp',
                                    ])
                                    ->helperText('تُعرض في بطاقة العرض — JPG/PNG/WEBP حتى 5MB')
                                    ->columnSpanFull(),

                                // ═══ الفيديو (اختياري) ═══
                                FileUpload::make('video')
                                    ->label('الفيديو الترويجي (اختياري)')
                                    ->disk('public')
                                    ->directory('promotions/videos')
                                    ->maxSize(51200)                             // 50 MB
                                    ->acceptedFileTypes([
                                        'video/mp4',
                                        'video/webm',
                                        'video/quicktime',                       // .mov من iPhone
                                    ])
                                    ->helperText('MP4 / WEBM / MOV — حتى 50MB. يُعرض في صفحة العرض التفصيلية.')
                                    ->columnSpanFull(),


                                Toggle::make('is_featured')
                                    ->label('عرض مميز (البطاقة الكبرى)')
                                    ->default(false)
                                    ->inline(false),

                            ])
                            ->columns(1),


                        // ═══ الترجمات ═══
                        Tab::make('العربية')
                            ->icon('heroicon-o-language')
                            ->schema([
                                TextInput::make('title.ar')
                                    ->label('العنوان (عربي)')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('subtitle.ar')
                                    ->label('العنوان الفرعي (عربي)')
                                    ->maxLength(255),

                                Textarea::make('description.ar')
                                    ->label('الوصف (عربي)')
                                    ->rows(3)
                                    ->maxLength(1000),

                                TextInput::make('cta_text.ar')
                                    ->label('نص الزر (عربي)')
                                    ->maxLength(50)
                                    ->placeholder('احصل على الكوبون'),
                            ]),

                        Tab::make('English')
                            ->icon('heroicon-o-language')
                            ->schema([
                                TextInput::make('title.en')
                                    ->label('Title (English)')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('subtitle.en')
                                    ->label('Subtitle (English)')
                                    ->maxLength(255),

                                Textarea::make('description.en')
                                    ->label('Description (English)')
                                    ->rows(3)
                                    ->maxLength(1000),

                                TextInput::make('cta_text.en')
                                    ->label('CTA text (English)')
                                    ->maxLength(50)
                                    ->placeholder('Get the coupon'),
                            ]),

                        Tab::make('Nederlands')
                            ->icon('heroicon-o-language')
                            ->schema([
                                TextInput::make('title.nl')
                                    ->label('Titel (Nederlands)')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('subtitle.nl')
                                    ->label('Ondertitel (Nederlands)')
                                    ->maxLength(255),

                                Textarea::make('description.nl')
                                    ->label('Beschrijving (Nederlands)')
                                    ->rows(3)
                                    ->maxLength(1000),

                                TextInput::make('cta_text.nl')
                                    ->label('CTA tekst (Nederlands)')
                                    ->maxLength(50)
                                    ->placeholder('Ontvang de coupon'),
                            ]),

                        // ═══ الفترة والترتيب ═══
                        Tab::make('الفترة والترتيب')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                DateTimePicker::make('starts_at')
                                    ->label('يبدأ في')
                                    ->nullable()
                                    ->helperText('اتركه فارغًا ليبدأ فورًا'),

                                DateTimePicker::make('ends_at')
                                    ->label('ينتهي في')
                                    ->nullable()
                                    ->after('starts_at')
                                    ->helperText('اتركه فارغًا لبلا نهاية'),

                                TextInput::make('sort_order')
                                    ->label('ترتيب الظهور')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->helperText('الأصغر يظهر أولًا'),

                                Toggle::make('is_active')
                                    ->label('مفعّل')
                                    ->default(true)
                                    ->inline(false),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
