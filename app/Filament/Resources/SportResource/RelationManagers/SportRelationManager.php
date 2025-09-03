<?php

namespace App\Filament\Resources\SportResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SportRelationManager extends RelationManager
{
    protected static string $relationship = 'sportModality';
    protected static ?string $label = 'Modalidade';
    protected static ?string $pluralLabel = 'Modalidades';
    protected static ?string $pluralModelLabel = 'Modalidades';
    protected static ?string $title = 'Modalidades';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('name')
                    ->label('Nome da Modalidade')
                    ->placeholder('Sub-17....')
                    ->required()
                    ->maxLength(255),

                Select::make('gender_id')
                    ->label('Gênero')
                    ->required()
                    ->relationship(
                        name: 'gender',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query->where('sport_modality', true)
                    ),

                TextInput::make('min_age')
                    ->label('Idade Mínima')
                    ->required()
                    ->suffix('anos')
                    ->numeric(),

                TextInput::make('max_age')
                    ->label('Idade Máxima')
                    ->required()
                    ->suffix('anos')
                    ->numeric(),

                TextInput::make('min_weight')
                    ->label('Peso Mínimo')
                    ->suffix('kg')
                    ->numeric(),

                TextInput::make('max_weight')
                    ->label('Peso Máximo')
                    ->suffix('kg')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('name')->sortable()->searchable()->label('Nome da Modalidade'),
                TextColumn::make('gender.name')->sortable()->searchable()->label('Gênero'),
                TextColumn::make('min_age')->sortable()->searchable()->label('Idade Mínima')->suffix(' anos'),
                TextColumn::make('max_age')->sortable()->searchable()->label('Idade Máxima')->suffix(' anos'),
                TextColumn::make('min_weight')->sortable()->searchable()->label('Peso Mínimo')->suffix(' kg')->default('--'),
                TextColumn::make('max_weight')->sortable()->searchable()->label('Peso Máximo')->suffix(' kg')->default('--'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
