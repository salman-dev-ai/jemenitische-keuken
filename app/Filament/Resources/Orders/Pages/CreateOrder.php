<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }
}
