<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Gender;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GenderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GenderResource\RelationManagers;

class GenderResource extends Resource
{
    protected static ?string $model = Gender::class;
    protected static ?string $label = 'Gênero';
    protected static ?string $navigationGroup = 'Gêneros';
    protected static ?string $navigationLabel = 'Gêneros';
    protected static ?string $pluralModelLabel = 'Gêneros';

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

                Toggle::make('active')
                    ->required()
                    ->default(true)
                    ->label('Ativo?')
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!$state) {
                            $set('sport_modality', false);
                        }
                    }),

                Toggle::make('sport_modality')
                    ->required()
                    ->default(true)
                    ->label('Modalidade Esportiva?')
                    ->disabled(fn(Get $get): bool => !$get('active'))
                    ->dehydrated(true) // Sempre envia o valor
                    ->dehydrateStateUsing(function ($state, Get $get) {
                        // Força false se active for false
                        return $get('active') ? $state : false;
                    }),
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

                IconColumn::make('sport_modality')
                    ->label('Modalidade Esportiva?')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('active')
                    ->label('Ativo?')
                    ->boolean()
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
