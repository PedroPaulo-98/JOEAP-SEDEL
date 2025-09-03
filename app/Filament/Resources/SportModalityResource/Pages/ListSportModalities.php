<?php

namespace App\Filament\Resources\SportModalityResource\Pages;

use App\Filament\Resources\SportModalityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSportModalities extends ListRecords
{
    protected static string $resource = SportModalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
