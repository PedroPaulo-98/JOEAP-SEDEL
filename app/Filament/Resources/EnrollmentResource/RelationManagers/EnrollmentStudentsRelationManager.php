<?php

namespace App\Filament\Resources\EnrollmentResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class EnrollmentStudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollmentStudents';
    protected static ?string $label = 'Inscrição';
    protected static ?string $pluralLabel = 'Inscrições';
    protected static ?string $pluralModelLabel = 'Inscrições';
    protected static ?string $title = 'Inscrições';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('student_id')
                    ->label('Aluno')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {
                            $user = auth()->user();

                            if ($user->hasRole('super_admin')) {
                                return $query;
                            }

                            return $query->whereHas('institution.user', fn($q) => $q->where('users.id', $user->id));
                        }
                    ),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.name')->label('Aluno')->searchable()->sortable(),
                TextColumn::make('student.institution.name')->label('Escola')->searchable()->sortable(),
                TextColumn::make('student.shirt_size')->label('Tam. Camisa')->searchable()->sortable(),
                TextColumn::make('student.weight')->label('Peso')->searchable()->sortable()->suffix(' kg'),

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
