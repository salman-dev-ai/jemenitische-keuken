<?php

namespace App\Filament\Resources\Allergens\Schemas;

use App\Enums\AllergenType;
use App\Models\Allergen;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * نموذج إدارة عنصر واحد من قاموس "معلومات الحساسية والعلامات الغذائية".
 */
class AllergenForm
{
    /**
     * أيقونات Lucide مقترحة (قابلة للكتابة اليدوية أيضاً).
     *
     * @var array<int, string>
     */
    private const SUGGESTED_ICONS = [
        'lucide-wheat',
        'lucide-wheat-off',
        'lucide-milk',
        'lucide-milk-off',
        'lucide-egg',
        'lucide-egg-off',
        'lucide-nut',
        'lucide-nut-off',
        'lucide-bean',
        'lucide-fish',
        'lucide-shrimp',
        'lucide-shell',
        'lucide-leaf',
        'lucide-flower',
        'lucide-salad',
        'lucide-vegan',
        'lucide-flask-round',
        'lucide-wine',
        'lucide-circle-dot',
        'lucide-badge-check',
        'lucide-candy',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | الأسماء المترجمة
                |--------------------------------------------------------------------------
                */

                Tabs::make('ترجمات العنصر')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('🇸🇦 العربية')
                            ->icon('lucide-languages')
                            ->schema([
                                TextInput::make('name.ar')
                                    ->label('الاسم بالعربية')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Tab::make('🇬🇧 English')
                            ->icon('lucide-languages')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (English)')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    // توليد الرابط الثابت تلقائياً من الاسم الإنجليزي
                                    ->afterStateUpdated(function (Set $set, ?string $state, ?string $old): void {
                                        if (filled($state) && $state !== $old) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                            ]),

                        Tab::make('🇳🇱 Nederlands')
                            ->icon('lucide-languages')
                            ->schema([
                                TextInput::make('name.nl')
                                    ->label('Naam (Nederlands)')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | التصنيف والأيقونة
                |--------------------------------------------------------------------------
                */

                Section::make('التصنيف والأيقونة')
                    ->description('يحدد التصنيف لون الشارة في واجهة العملاء، والأيقونة تُعرض داخل بطاقة الطبق.')
                    ->icon('lucide-triangle-alert')
                    ->columns(2)
                    ->schema([

                        TextInput::make('slug')
                            ->label('الرابط الثابت (Slug)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->maxLength(100)
                            ->helperText('يُستخدم برمجياً وبينما الترحيل — لا يظهر للعميل.'),

                        Select::make('type')
                            ->label('التصنيف')
                            ->options(AllergenType::options())
                            ->default(AllergenType::Allergen->value)
                            ->required()
                            ->native(false),

                        TextInput::make('icon')
                            ->label('أيقونة Lucide')
                            ->placeholder('lucide-wheat')
                            ->datalist(self::SUGGESTED_ICONS)
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->dehydrateStateUsing(fn (?string $state): ?string => Allergen::normalizeIcon($state))
                            // التحقق من وجود الأيقونة فعلاً في مكتبة lucide-icons
                            ->rule(function (): Closure {
                                return function (string $attribute, mixed $value, Closure $fail): void {
                                    if (blank($value)) {
                                        return;
                                    }

                                    $icon = Allergen::normalizeIcon((string) $value);

                                    if ($icon === null) {
                                        $fail('صيغة اسم الأيقونة غير صحيحة.');

                                        return;
                                    }

                                    if (rescue(fn (): mixed => svg($icon), null, false) === null) {
                                        $fail('لا توجد أيقونة بهذا الاسم: '.$icon);
                                    }
                                };
                            })
                            ->helperText('اكتب اسم الأيقونة من مكتبة Lucide بدون أو مع بادئة lucide- (مثال: wheat أو lucide-wheat).'),

                        Placeholder::make('icon_preview')
                            ->label('معاينة الأيقونة')
                            ->content(function (Get $get): HtmlString {
                                $icon = Allergen::normalizeIcon($get('icon'));

                                if ($icon === null) {
                                    return new HtmlString(
                                        '<span class="text-xs text-gray-500">لم تُضبط أيقونة بعد.</span>'
                                    );
                                }

                                $markup = rescue(
                                    fn (): ?string => svg($icon, 'w-7 h-7 text-primary-600')->toHtml(),
                                    null,
                                    false,
                                );

                                if ($markup === null) {
                                    return new HtmlString(
                                        '<span class="text-xs text-danger-600">اسم الأيقونة غير موجود: '
                                        .e($icon).'</span>'
                                    );
                                }

                                return new HtmlString(
                                    '<div class="flex items-center gap-3">'.$markup
                                    .'<code class="text-xs">'.e($icon).'</code></div>'
                                );
                            }),

                        FileUpload::make('image_path')
                            ->label('صورة بديلة (اختيارية)')
                            ->image()
                            ->imageEditor()
                            ->directory('allergens')
                            ->disk('public')
                            ->maxSize(1024)
                            // تصغير تلقائي في المتصفح قبل الرفع لتسريع الموقع (بلا تكبير للصور الصغيرة)
                            ->automaticallyResizeImagesMode('contain')
                            ->automaticallyResizeImagesToWidth(512)
                            ->automaticallyResizeImagesToHeight(512)
                            ->automaticallyUpscaleImagesWhenResizing(false)
                            ->columnSpanFull()
                            ->helperText('عند رفع صورة تُعرض بدل الأيقونة.'),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | الترتيب والتفعيل
                |--------------------------------------------------------------------------
                */

                Section::make('الترتيب والتفعيل')
                    ->icon('lucide-sliders-horizontal')
                    ->columns(2)
                    ->schema([

                        TextInput::make('sort_order')
                            ->label('ترتيب العرض')
                            ->numeric()
                            ->integer()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('كلما كان الرقم أصغر ظهر العنصر أولاً.'),

                        Toggle::make('is_active')
                            ->label('مفعّل')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('العناصر المعطّلة لا تظهر في واجهة العملاء ولا في اختيار الأطباق.'),

                    ]),

            ]);
    }
}
