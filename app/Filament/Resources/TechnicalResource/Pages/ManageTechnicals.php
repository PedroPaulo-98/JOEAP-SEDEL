<?php

namespace App\Filament\Resources\TechnicalResource\Pages;

use App\Filament\Resources\TechnicalResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTechnicals extends ManageRecords
{
    protected static string $resource = TechnicalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
