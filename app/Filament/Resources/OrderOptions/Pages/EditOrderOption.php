<?php

namespace App\Filament\Resources\OrderOptions\Pages;

use App\Filament\Resources\OrderOptions\OrderOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderOption extends EditRecord
{
    protected static string $resource = OrderOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
