<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use App\Models\Allergen;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set as UtilitiesSet;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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
                            ->icon('lucide-cake')
                            ->schema([

                                Grid::make(3)
                                    ->schema([

                                        Tabs::make('محتوى اللغات')
                                            ->columnSpanFull()
                                            ->tabs([
                                                Tabs\Tab::make('العربية 🇸🇦')
                                                    ->icon('lucide-languages')
                                                    ->schema([
                                                        TextInput::make('name.ar')
                                                            ->label('اسم الطبق')
                                                            ->required()
                                                            ->maxLength(255),

                                                        Textarea::make('description.ar')
                                                            ->label('وصف الطبق')
                                                            ->rows(3)
                                                            ->helperText('اكتب وصفاً جذاباً للطبق باللغة العربية.'),
                                                    ]),

                                                Tabs\Tab::make('English 🇬🇧')
                                                    ->icon('lucide-languages')
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
                                                    ->icon('lucide-languages')
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
                                    // تصغير تلقائي في المتصفح قبل الرفع لتسريع الموقع (بلا تكبير للصور الصغيرة)
                                    ->automaticallyResizeImagesMode('contain')
                                    ->automaticallyResizeImagesToWidth(1200)
                                    ->automaticallyResizeImagesToHeight(1200)
                                    ->automaticallyUpscaleImagesWhenResizing(false)
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
                    ->icon('lucide-tag')
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
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('sort_order'),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (MenuCategory $record) => $record->localized_name
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
                    ->icon('lucide-sliders-horizontal')
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
                |   المشروب المجاني
                |--------------------------------------------------------------------------
                */

                Section::make('المشروب المجاني  ')
                    ->icon('lucide-gift')
                    ->description('اربط الطبق بمشروب مجاني بسعر صفر — لا يؤثر على الإجمالي أو الضريبة.')
                    ->columns(2)
                    ->schema([

                        Toggle::make('offers_free_drink')
                            ->label('يقدّم مشروباً مجانياً')
                            ->default(false)
                            ->onColor('success')
                            ->live()
                            ->columnSpanFull(),

                        TextInput::make('free_drink_qty')
                            ->label('عدد المشروبات المجانية')
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->default(1)
                            ->visible(fn (Get $get): bool => (bool) $get('offers_free_drink')),

                        Select::make('free_drink_policy')
                            ->label('سياسة تطبيق العدد')
                            ->options([
                                MenuItem::FREE_DRINK_POLICY_PER_UNIT => 'لكل وحدة (يتضاعف مع الكمية)',
                                MenuItem::FREE_DRINK_POLICY_PER_LINE => 'حد لسطر الطبق (ثابت)',
                            ])
                            ->default(MenuItem::FREE_DRINK_POLICY_PER_UNIT)
                            ->native(false)
                            ->visible(fn (Get $get): bool => (bool) $get('offers_free_drink')),

                        Select::make('free_drink_item_ids')
                            ->label('المشروبات المسموحة')
                            ->multiple()
                            ->searchable()
                            ->options(fn () => MenuItem::query()
                                ->available()
                                ->whereHas('category', fn (Builder $q) => $q->where('is_drinks_category', true))
                                ->orderBy('sort_order')
                                ->get()
                                ->mapWithKeys(fn (MenuItem $m): array => [$m->id => $m->localized_name])
                                ->all())
                            ->helperText('تُعرض أصناف تصنيف المشروبات فقط. مشروب واحد ⇒ يُضاف تلقائياً، وأكثر ⇒ يختار العميل.')
                            ->visible(fn (Get $get): bool => (bool) $get('offers_free_drink'))
                            ->columnSpanFull(),

                    ]),

                /*
                                |--------------------------------------------------------------------------
                                | مسببات الحساسية
                                |--------------------------------------------------------------------------
                                */

                Section::make('معلومات الحساسية والعلامات الغذائية')
                    ->icon('lucide-triangle-alert')
                    ->description('اختر العناصر من القاموس المُدار في «الحساسية والعلامات» — تُعرض أيقوناتها تلقائياً في بطاقة الطبق.')
                    ->schema([

                        Select::make('allergens')
                            ->label('مسببات الحساسية والعلامات')
                            ->relationship(
                                name: 'allergens',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->active()->ordered(),
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn (Allergen $record): string => $record->localized_name
                            )
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->helperText('تُضاف العناصر وتُعدَّل من: إدارة المنيو ← الحساسية والعلامات.'),

                    ])
                    ->columns(1),

            ]);
    }
}
