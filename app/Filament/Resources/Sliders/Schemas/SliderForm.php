<?php

namespace App\Filament\Resources\Sliders\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class SliderForm
{

     public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // العمود الرئيسي (2/3)
                Section::make('محتوى الشريحة')
                    ->icon("lucide-image-play")
                    ->schema([
                        Tabs::make('translations')
                            ->tabs([
                                // ===== العربية =====
                                Tab::make('العربية (AR)')
                                    ->icon("lucide-languages")
                                    ->schema([
                                        TextInput::make('eyebrow.ar')
                                            ->label('النص العلوي (Eyebrow)')
                                            ->maxLength(255)
                                            ->placeholder('مثال: عرض خاص'),

                                        TextInput::make('title.ar')
                                            ->label('العنوان الرئيسي')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('مثال: مطعمنا اليمني الأصيل'),

                                        Textarea::make('subtitle.ar')
                                            ->label('النص الفرعي')
                                            ->rows(3)
                                            ->placeholder('وصف مختصر يظهر أسفل العنوان'),
                                    ]),

                                // ===== الهولندية =====
                                Tab::make('الهولندية (NL)')
                                    ->icon("lucide-languages")
                                    ->schema([
                                        TextInput::make('eyebrow.nl')
                                            ->label('Bovenste tekst (Eyebrow)')
                                            ->maxLength(255),

                                        TextInput::make('title.nl')
                                            ->label('Hoofdtitel')
                                            ->required()
                                            ->maxLength(255),

                                        Textarea::make('subtitle.nl')
                                            ->label('Ondertitel')
                                            ->rows(3),
                                    ]),

                                // ===== الإنجليزية =====
                                Tab::make('الإنجليزية (EN)')
                                    ->icon("lucide-languages")
                                    ->schema([
                                        TextInput::make('eyebrow.en')
                                            ->label('Eyebrow Text')
                                            ->maxLength(255),

                                        TextInput::make('title.en')
                                            ->label('Main Title')
                                            ->required()
                                            ->maxLength(255),

                                        Textarea::make('subtitle.en')
                                            ->label('Subtitle')
                                            ->rows(3),
                                    ]),
                            ])
                            ->activeTab(1)
                            ->persistTabInQueryString(),
                    ])
                    ->columnSpan(['lg' => 2]),

                // الشريط الجانبي (1/3)
                Section::make('الإعدادات')
                    ->icon("lucide-settings")
                    ->schema([
                        FileUpload::make('image')

                            ->label('صورة الشريحة')
                            ->hintIcon("lucide-image-down")
                            ->image()
                            ->imageEditor()
                            ->directory('sliders')
                            ->disk('public')

                            ->required(),

                        Toggle::make('is_active')
                            ->label('مفعل')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
