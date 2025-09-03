<?php

namespace App\Filament\Resources\SportModalityResource\Pages;

use App\Filament\Resources\SportModalityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSportModality extends EditRecord
{
    protected static string $resource = SportModalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
