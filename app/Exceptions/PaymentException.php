<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

// الرسالة تُعرض للمستخدم،  ة)
class PaymentException extends RuntimeException
{
    //
    protected $message = 'Payment failed.';
}
