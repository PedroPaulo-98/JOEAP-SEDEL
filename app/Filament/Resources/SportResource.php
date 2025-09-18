<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sport;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SportResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SportResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class SportResource extends Resource
{
    protected static ?string $model = Sport::class;
    protected static ?string $label = 'Esporte';
    protected static ?string $navigationGroup = 'Eventos';
    protected static ?string $navigationLabel = 'Esportes';
    protected static ?string $pluralModelLabel = 'Esportes';
    protected static ?string $navigationIcon = 'phosphor-court-basketball';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome do Esporte')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome do Esporte')->sortable()->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SportRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSports::route('/'),
            'create' => Pages\CreateSport::route('/create'),
            'edit' => Pages\EditSport::route('/{record}/edit'),
        ];
    }
}
