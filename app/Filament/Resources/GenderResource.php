<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenderResource\Pages;
use App\Filament\Resources\GenderResource\RelationManagers;
use App\Models\Gender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class GenderResource extends Resource
{
    protected static ?string $model = Gender::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Gênero'),
                TextInput::make('abbreviation')
                    ->required()
                    ->maxLength(255)
                    ->label('Abreviação'),
                Toggle::make('sport_modality')
                    ->required()
                    ->default(true)
                    ->label('Modalidade Esportiva?'),
                Toggle::make('active')
                    ->required()
                    ->default(true)
                    ->label('Ativo?'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Gênero')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('abbreviation')
                    ->label('Abreviação')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('sport_modality')
                    ->label('Modalidade Esportiva?')
                    ->sortable(),
                ToggleColumn::make('active')
                    ->label('Ativo?')
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGenders::route('/'),
            'create' => Pages\CreateGender::route('/create'),
            'edit' => Pages\EditGender::route('/{record}/edit'),
        ];
    }
}
