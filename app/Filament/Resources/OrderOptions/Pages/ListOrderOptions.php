<?php

namespace App\Filament\Resources\OrderOptions\Pages;

use App\Filament\Resources\OrderOptions\OrderOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrderOptions extends ListRecords
{
    protected static string $resource = OrderOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
