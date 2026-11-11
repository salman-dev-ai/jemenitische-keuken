<?php

declare(strict_types=1);

/**
 * تصنيف عنصر "معلومات الحساسية":
 *  - Allergen : مسبب حساسية (Gluten، Melk، Noten، Ei ...).
 *  - Label    : علامة غذائية (Halal، Vegan، Vegetarisch ...).
 * يُستخدم في Filament Select/Filter، ويحدد لون الشارة في واجهة العملاء.
 */

namespace App\Enums;

use App\Enums\Concerns\HasEnumHelpers;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AllergenType: string implements HasColor, HasLabel
{
    use HasEnumHelpers;

    case Allergen = 'allergen';
    case Label = 'label';

    /**
     * التسمية المترجمة — تُعرض في Filament Tables و Forms.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Allergen => __('enums.allergen_type.allergen'),
            self::Label => __('enums.allergen_type.label'),
        };
    }

    /**
     * لون الـ Badge في Filament — مسبب حساسية = كهرماني، علامة غذائية = أخضر.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Allergen => 'warning',
            self::Label => 'success',
        };
    }

    /**
     * هل هذا العنصر علامة غذائية (وليس مسبب حساسية)؟
     */
    public function isDietaryLabel(): bool
    {
        return $this === self::Label;
    }
}
