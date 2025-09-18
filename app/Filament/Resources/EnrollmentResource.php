<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Enrollment;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EnrollmentResource\Pages;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Filament\Resources\EnrollmentResource\RelationManagers;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $label = 'Inscrição';
    protected static ?string $navigationGroup = 'Eventos';
    protected static ?string $navigationLabel = 'Inscrições';
    protected static ?string $pluralModelLabel = 'Inscrições';
    protected static ?string $navigationIcon = 'phosphor-list-checks';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('event_id')
                    ->label('Evento')
                    ->searchable()
                    ->preload()
                    ->relationship('event', 'name')
                    ->required(),

                Select::make('sport_id')
                    ->label('Esporte')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => $set('sport_modality_id', null))
                    ->relationship('sport', 'name'),

                Select::make('sport_modality_id')
                    ->label('Modalidade')
                    ->required()
                    ->options(function (Get $get) {
                        $sportId = $get('sport_id');

                        if (!$sportId) {
                            return [];
                        }

                        return \App\Models\SportModality::where('sport_id', $sportId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload(),
            ])->disabled(auth()->user()->hasRole('super_admin') ? false : true);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name')->label('Evento')->sortable()->searchable(),
                TextColumn::make('sport.name')->label('Esporte')->sortable()->searchable(),
                TextColumn::make('sportModality.name')->label('Mod. - Nome')->sortable()->searchable(),
                TextColumn::make('sportModality.gender.name')->label('Mod. - Gênero')->sortable()->searchable(),
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
