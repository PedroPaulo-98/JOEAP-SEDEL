<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $label = 'Estudante';
    protected static ?string $navigationGroup = 'Estudantes';
    protected static ?string $navigationLabel = 'Estudantes';
    protected static ?string $pluralModelLabel = 'Estudantes';
    protected static ?string $navigationIcon = 'phosphor-student';

    public static function form(Form $form): Form
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
                    ]),

                Select::make('institution_id')->label('Instituição/Escola')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('institution', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome do Aluno')->sortable()->searchable(),
                TextColumn::make('age')->label('Idade do Aluno')->sortable()->searchable()->suffix(' anos'),
                TextColumn::make('weight')->label('Peso do Aluno')->sortable()->searchable()->suffix(' kg'),
                TextColumn::make('shirt_size')->label('Tamanho da Camiseta')->sortable()->searchable(),
                TextColumn::make('institution.name')->label('Instituição/Escola')->sortable()->searchable(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
