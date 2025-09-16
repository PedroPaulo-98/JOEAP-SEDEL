<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Enrollment;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use Illuminate\Database\Eloquent\Relations\Relation;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $label = 'Inscrição';
    protected static ?string $navigationGroup = 'Inscrições';
    protected static ?string $navigationLabel = 'Inscrições';
    protected static ?string $pluralModelLabel = 'Inscrições';
    protected static ?string $navigationIcon = 'phosphor-list-checks';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('event_id')
                    ->label('Evento')
                    ->relationship('event', 'name')
                    ->required(),
                Select::make('sport_id')
                    ->label('Esporte')
                    ->required()
                    ->relationship('sport', 'name'),
            ])->disabled(auth()->user()->hasRole('super_admin') ? false : true);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name')->label('Evento')->sortable()->searchable(),
                TextColumn::make('sport.name')->label('Esporte')->sortable()->searchable(),
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
            RelationManagers\EnrollmentStudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // Filtra apenas os estudantes da instituição do usuário
        return $query->whereHas('enrollmentStudents.student.institution.user', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }
}
