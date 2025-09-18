<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Institution;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InstitutionResource\Pages;
use App\Filament\Resources\InstitutionResource\RelationManagers;
use App\Filament\Resources\InstitutionResource\RelationManagers\StudentRelationManager;
use App\Filament\Resources\InstitutionResource\RelationManagers\TechnicalRelationManager;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;
    protected static ?string $label = 'Instituição/Escola';
    protected static ?string $navigationGroup = 'Institucional';
    protected static ?string $navigationLabel = 'Instituições/Escolas';
    protected static ?string $pluralModelLabel = 'Instituições/Escolas';
    protected static ?string $navigationIcon = 'phosphor-building-apartment';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nome da Instituição/Escola')
                    ->maxLength(255),
            ])->disabled(fn() => !Auth::user()->hasRole('super_admin'));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome da Instituição/Escola')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentRelationManager::class,
            TechnicalRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstitutions::route('/'),
            'create' => Pages\CreateInstitution::route('/create'),
            'edit' => Pages\EditInstitution::route('/{record}/edit'),
            'view' => Pages\ViewInstitution::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // Filtra apenas as instituições do usuário
        return $query->whereHas('user', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }
}
