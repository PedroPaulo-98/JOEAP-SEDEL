<?php

namespace App\Filament\Resources\InstitutionResource\RelationManagers;

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

class StudentRelationManager extends RelationManager
{
    protected static string $relationship = 'student';
    protected static ?string $label = 'Estudante/Atleta';
    protected static ?string $pluralLabel = 'Estudantes/Atletas';
    protected static ?string $pluralModelLabel = 'Estudantes/Atletas';
    protected static ?string $title = 'Estudantes/Atletas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nome do Aluno')
                    ->required()
                    ->maxLength(255),

                Select::make('gender_id')->label('Gênero')
                    ->required()
                    ->relationship(
                        name: 'gender',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query->where('active', true)
                    ),

                TextInput::make('age')->label('Idade do Aluno')
                    ->required()
                    ->numeric()
                    ->suffix(' anos')
                    ->minvalue(1),

                TextInput::make('weight')->label('Peso do Aluno')
                    ->required()
                    ->numeric()
                    ->suffix(' kg')
                    ->minvalue(1),

                Select::make('shirt_size')->label('Tamanho da Camiseta')
                    ->required()
                    ->options([
                        'P' => 'P',
                        'M' => 'M',
                        'G' => 'G',
                        'GG' => 'GG',
                        'XG' => 'XG',
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('name')->label('Nome do Aluno')->sortable()->searchable(),
                TextColumn::make('age')->label('Idade do Aluno')->sortable()->searchable()->suffix(' anos'),
                TextColumn::make('weight')->label('Peso do Aluno')->sortable()->searchable()->suffix(' kg'),
                TextColumn::make('shirt_size')->label('Tamanho da Camiseta')->sortable()->searchable(),
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
