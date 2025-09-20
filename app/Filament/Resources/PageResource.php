<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\PageText;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\UploadedFile as TemporaryUploadedFile;

class PageResource extends Resource
{
    protected static ?string $model = PageText::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Textos do Banner')
                    ->schema([
                        Forms\Components\TextInput::make('data.banner_title')
                            ->label('Título do Banner')
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\TextInput::make('data.subtitle_banner')
                            ->label('Subtítulo do Banner')
                            ->columnSpan(['lg' => 2]),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Fotos')
                    ->schema([
                        Forms\Components\FileUpload::make('data.banner')
                            ->label('Foto JPG')
                            ->directory('people/photos')
                            ->acceptedFileTypes(['image/jpeg', 'image/jpg'])
                            ->image()
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->helperText('Apenas arquivos JPG/JPEG são permitidos (máx. 10MB).')
                            ->preserveFilenames(false)
                            ->getUploadedFileNameForStorageUsing(
                                function (TemporaryUploadedFile $file): string {
                                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                                    $extension = $file->getClientOriginalExtension();
                                    $safeName = str($originalName)->slug();

                                    return 'photo-' . md5(time()) . '-' . $safeName . '.' . $extension;
                                }
                            )
                            ->rules([
                                'required',
                                'image',
                                'mimes:jpeg,jpg',
                                'max:10240',
                                function (): \Closure {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        try {
                                            // Verificação básica de imagem
                                            $imageInfo = getimagesize($value->getRealPath());

                                            if ($imageInfo === false) {
                                                $fail('O arquivo não é uma imagem válida.');
                                                return;
                                            }

                                            // Verificação adicional de segurança
                                            $content = file_get_contents($value->getRealPath());

                                            // Verifica se é realmente um JPEG (assinatura binária)
                                            if (bin2hex(substr($content, 0, 2)) !== 'ffd8') {
                                                $fail('O arquivo não é um JPG válido.');
                                                return;
                                            }

                                            // Verifica por possíveis códigos maliciosos (básico)
                                            if (preg_match('/<\?php|eval\(|base64_decode|script|iframe/i', $content)) {
                                                $fail('A imagem contém conteúdo potencialmente perigoso.');
                                                return;
                                            }
                                        } catch (\Exception $e) {
                                            $fail('Não foi possível verificar a imagem: ' . $e->getMessage());
                                        }
                                    };
                                },
                            ]),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última atualização')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
