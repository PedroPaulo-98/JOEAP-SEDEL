<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicalResource\Pages;
use App\Filament\Resources\TechnicalResource\RelationManagers;
use App\Models\Technical;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TechnicalResource extends Resource
{
    protected static ?string $model = Technical::class;
    protected static ?string $label = 'Técnico';
    protected static ?string $navigationGroup = 'Técnicos';
    protected static ?string $navigationLabel = 'Técnicos';
    protected static ?string $pluralModelLabel = 'Técnicos';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('institution_id')->label('Instituição/Escola')
                    ->relationship('institution', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')->label('Nome do Técnico')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('institution.name')->label('Instituição/Escola')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Nome do Técnico')
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTechnicals::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return $query;
        }
        return $query->whereHas('institution', fn($q) => $q->whereHas('user', fn($q) => $q->where('users.id', $user->id)));
    }
}
