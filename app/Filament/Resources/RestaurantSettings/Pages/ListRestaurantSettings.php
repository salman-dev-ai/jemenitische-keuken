<?php

namespace App\Filament\Resources\RestaurantSettings\Pages;

use App\Filament\Resources\RestaurantSettings\RestaurantSettingResource;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListRestaurantSettings extends ListRecords
{
    // add text translation to the list page
    use Translatable;

    protected static string $resource = RestaurantSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // add locale switcher to the list page
            LocaleSwitcher::make(),
         
        ];

    }
}



