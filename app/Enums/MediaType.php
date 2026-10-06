<?php

declare(strict_types=1);

/**
 * نوع الوسائط المخزّنة في Spatie Media Library (صورة / فيديو).
 * يُستخدم في Filament Resources لتصنيف المرفقات وعرضها بشكل مختلف.
 * يطبّق HasColor لتمييز النوعين بصرياً في الجداول.
 */

namespace App\Enums;

use App\Enums\Concerns\HasEnumHelpers;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MediaType: string implements HasColor, HasLabel
{
    use HasEnumHelpers;

    case Image = 'image';
    case Video = 'video';

    /**
     * التسمية المترجمة — تُعرض في Filament Tables و Forms.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Image => __('enums.media_type.image'),
            self::Video => __('enums.media_type.video'),
        };
    }

    /**
     * لون الـ Badge — صورة = بنفسجي، فيديو = أحمر.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Image => 'purple',
            self::Video => 'danger',
        };
    }
}