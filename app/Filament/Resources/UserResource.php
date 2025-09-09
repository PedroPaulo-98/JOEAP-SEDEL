<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Usuário';
    protected static ?string $navigationGroup = 'Usuários';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $pluralModelLabel = 'Usuários';
    protected static ?string $navigationIcon = 'phosphor-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('institution_id')->label('Instituição Vinculada')
                    ->preload()
                    ->required()
                    ->searchable()
                    ->relationship('institution', 'name'),

                TextInput::make('name')->label('Nome do Usuário')
                    ->required(),

                TextInput::make('email')->label('Email do Usuário')
                    ->required(),

                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->revealable()
                    ->required(fn($operation) => $operation === 'create')
                    ->visible(function ($record, $operation) {
                        if ($operation === 'create') return true;
                        return Auth::check() && Auth::id() === $record->id;
                    })
                    ->disabled(function ($record, $operation) {
                        if ($operation === 'create') return false;
                        return Auth::id() !== $record->id;
                    })
                    ->helperText(function ($record, $operation) {
                        if ($operation === 'edit' && Auth::id() !== $record->id) {
                            return 'Apenas o próprio usuário pode alterar sua senha.';
                        }
                        return null;
                    }),

                Select::make('roles')
                    ->label('Função')
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('guard_name', 'web')
                    )
                    ->options(Role::all()->pluck('name', 'id')) // Carrega todas as roles
                    ->multiple(false) // Para seleção única
                    ->required()
                    ->default(fn() => Role::where('name', 'super_admin')->first()?->id),

                Section::make('Redefinição de Senha')
                    ->schema([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('quickResetPassword')
                                ->label('Resetar Senha')
                                ->icon('heroicon-o-key')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading('Resetar Senha')
                                ->modalDescription('Tem certeza que deseja resetar a senha? A nova senha será "prodap@2025"')
                                ->modalSubmitActionLabel('Sim, resetar senha')
                                ->action(function ($record) {
                                    $record->update([
                                        'password' => bcrypt('prodap@2025')
                                    ]);

                                    Notification::make()
                                        ->title('Senha resetada com sucesso!')
                                        ->body('A senha foi redefinida para "prodap@2025"')
                                        ->success()
                                        ->send();
                                })
                        ])
                    ])
                    ->visible(function ($record, $operation) {
                        return $operation === 'edit' && Auth::id() !== $record->id;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome'),
                TextColumn::make('email')->label('Email')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
