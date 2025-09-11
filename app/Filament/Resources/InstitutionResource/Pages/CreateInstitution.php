<?php

namespace App\Filament\Resources\InstitutionResource\Pages;

use App\Filament\Resources\InstitutionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInstitution extends CreateRecord
{
    protected static string $resource = InstitutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Criar')
                ->successRedirectUrl(fn(): string => static::getResource()::getUrl('index'))
        ];
    }

    // Ou sobrescrevendo o método getRedirectUrl()
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
