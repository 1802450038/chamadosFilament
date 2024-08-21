<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrinterResource\Pages;
use App\Filament\Resources\PrinterResource\RelationManagers;
use App\Models\Printer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PrinterResource extends Resource
{
    protected static ?string $model = Printer::class;

    protected static ?string $navigationIcon = 'heroicon-o-printer';
    protected static ?string $modelLabel = 'Impressora';
    protected static ?string $pluralModelLabel = 'Impressoras';
    protected static ?string $slug = 'impressoras';
    protected static ?string $navigationGroup = 'Equipamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('Local')->description('Informações sobre o local da impressora')->schema([
                        Forms\Components\Hidden::make('user_id')->default(auth()->id())
                            ->label('usuario'),
                        Forms\Components\Select::make('location_id')
                            ->label('Local')
                            ->relationship('location', 'sector')
                            ->searchable()
                            ->optionsLimit(20)
                            ->preload()
                            ->required(),
                    ]),
                    Forms\Components\Section::make('Impressora')->description('Informações sobre a impressora')->schema([
                        Forms\Components\TextInput::make('brand')
                            ->label('Marca')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('ip')
                            ->label('IP')
                            ->maxLength(255)
                            ->default('DHCP'),
                        Forms\Components\Radio::make('colored')
                            ->label('Colorida')
                            ->boolean()
                            ->options([
                                '1' => 'Colorida',
                                '0' => 'Preto e branco',
                            ])
                            ->descriptions([
                                '1' => 'Tinta colorida.',
                                '0' => 'Apenas preto e branco.',
                            ])->inline()
                            ->inlineLabel(false),
              
                        Forms\Components\TextInput::make('identifier')
                            ->label('Identificador')
                            ->maxLength(255)
                            ->default('Sem contrato'),
                    ])->columns(2)

                ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location.sector')
                    ->label('Local')
                    ->color('primary')
                    ->icon('heroicon-o-map-pin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP')
                    ->searchable(),
                Tables\Columns\IconColumn::make('colored')
                    ->label('Colorida')

                    ->boolean(),
                Tables\Columns\TextColumn::make('identifier')
                    ->label('Itendificador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListPrinters::route('/'),
            'create' => Pages\CreatePrinter::route('/create'),
            'edit' => Pages\EditPrinter::route('/{record}/edit'),
        ];
    }
}
