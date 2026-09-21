<?php

namespace App\Filament\Resources\OrderOptions\Pages;

use App\Filament\Resources\OrderOptions\OrderOptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderOption extends CreateRecord
{
    protected static string $resource = OrderOptionResource::class;
}
