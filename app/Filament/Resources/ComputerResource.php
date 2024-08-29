<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComputerResource\Pages;
use App\Models\Computer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class ComputerResource extends Resource
{
    protected static ?string $model = Computer::class;


    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $modelLabel = 'Computador';
    protected static ?string $pluralModelLabel = 'Computadores';
    protected static ?string $slug = 'computadores';
    protected static ?string $navigationGroup = 'Equipamentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make()->schema([


                    Forms\Components\Section::make('Computador')->description('Informações sobre o computador')->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Hidden::make('user_id')->default(auth()->id())->label('Registrado por'),
                            Forms\Components\TextInput::make('patrimony')
                                ->label('Patrimonio')
                                ->placeholder('Codigo do patrimonio ou alguma identificação')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('brand')
                                ->label('Marca')
                                ->required()
                                ->placeholder("Hp, Dell, Pc Top...")
                                ->maxLength(255),
                        ])->columns(2),
                    ])->columnSpan(1),

                    Forms\Components\Section::make('Local')->description('Informações sobre o local do computador')->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\Select::make('location_id')
                                ->label('Localização')
                                ->relationship('location', 'sector')
                                ->searchable('sector')
                                ->preload()
                                ->required()
                                ->optionsLimit(20)
                        ])->columns(1),
                    ])->columnSpan(1),
                    Forms\Components\Section::make('Extra')->description('Informações adicionais sobre o computador')->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Imagem')
                                ->image(),
                            Forms\Components\RichEditor::make('description')
                                ->label('Descrição')
                                ->placeholder("Digite a especificação ou alguma informação util, como senha ou se contem alguma peça importante")
                                ->maxLength(255),
                        ])->columns(2),
                    ])->columnSpan(2),

                ])->columns(2)
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Computador')->schema([
                    TextEntry::make('patrimony')->label('Patrimonio')->icon('heroicon-o-qr-code'),
                    TextEntry::make('brand')->label('Marca'),
                ])->columns(2)->description("Informações adicionais do computador"),
                Section::make('Extra')->schema([
                    Grid::make()->schema([
                        TextEntry::make('user.name')->label('Registrado por ')->icon('heroicon-o-user'),
                        TextEntry::make('description')->label('Descrição')->html(),
                    ])->columnSpan(1)->columns(1),
                    ImageEntry::make('image')->label('Imagem')->size(80)->circular(),
                ])->columns(2)->description("Informações do computador"),
            ]);
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagem')
                    ->circular(),
                Tables\Columns\TextColumn::make('patrimony')
                    ->label('Patrimonio')
                    ->icon('heroicon-o-qr-code')
                    ->badge()
                    ->color('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->html()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location.sector')
                    ->label('Local')
                    ->color('primary')
                    ->icon('heroicon-o-map-pin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data registro')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Data atualização')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                SelectFilter::make('Local')
                    ->relationship('location', 'sector')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
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
            'index' => Pages\ListComputers::route('/'),
            'create' => Pages\CreateComputer::route('/create'),
            'view' => Pages\ViewComputer::route('{record}'),
            'edit' => Pages\EditComputer::route('/{record}/edit'),
        ];
    }
}
