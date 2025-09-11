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
use Filament\Resources\RelationManagers\RelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Usuário';
    protected static ?string $navigationGroup = 'Usuários';
    // protected static ?string $navigationLabel = 'Usuários';
    public static function getNavigationLabel(): string
    {
        if (Auth::check() && Auth::user()->hasRole('super_admin')) {
            return 'Usuários';
        }

        return 'Meu Perfil';
    }
    protected static ?string $pluralModelLabel = 'Usuários';
    protected static ?string $navigationIcon = 'phosphor-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados do Usuário')
                    ->schema([
                        TextInput::make('name')->label('Nome do Usuário')
                            ->required(),

                        TextInput::make('email')->label('Email do Usuário')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Segurança')
                    ->schema([
                        TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->revealable()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            // ->nullable()
                            ->disabled(
                                fn($record, $operation): bool =>
                                $operation === 'edit' && Auth::id() !== $record->id
                            )
                            ->visible(
                                fn($record, $operation): bool =>
                                $operation === 'create' || Auth::id() === $record->id
                            ),
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
                        ])->visible(function ($record, $operation) {
                            return $operation === 'edit' && Auth::id() !== $record->id;
                        })
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Instituições/Escolas Vincualdas ao Usuário')
                    ->schema([
                        Select::make('institution')->label('Instituição Vinculada')
                            ->preload()
                            ->multiple(true)
                            ->searchable()
                            ->relationship('institution', 'name'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Permissões do Usuário')
                    ->schema([
                        Select::make('roles')
                            ->label('Função')
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('guard_name', 'web')
                            )
                            ->options(Role::all()->pluck('name', 'id'))
                            ->multiple(false)
                            ->required()
                            ->default(fn() => Role::where('name', 'super_admin')->first()?->id),
                    ])
                    ->columns(1)
                    ->columnSpanFull()
                    ->visible(function ($record, $operation) {
                        return Auth::check() && Auth::user()->hasRole('super_admin');
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Se for super_admin, retorna todos os usuários
        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // Se não for super_admin, retorna apenas o próprio usuário
        return $query->where('id', $user->id);
    }

    public static function canViewAny(): bool
    {
        return Auth::check();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
