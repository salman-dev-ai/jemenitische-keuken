<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    protected $fillable = ['provider', 'event_id', 'event_type', 'processed_at'];

    protected function casts(): array
    {
        return ['processed_at' => 'datetime'];
    }
}
