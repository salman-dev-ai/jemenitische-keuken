<?php

namespace App\Filament\Resources\RestaurantSettings\Pages;

use App\Filament\Resources\RestaurantSettings\RestaurantSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurantSetting extends CreateRecord
{
    protected static string $resource = RestaurantSettingResource::class;
}
