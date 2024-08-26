<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\RelationManagers\LocationRelationManager;
use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Filament\Resources\LocationResource\RelationManagers\AddressRelationManager;
use App\Models\Address;
use App\Models\Location;
use Dompdf\Css\Color;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;


    protected static ?string $modelLabel = 'Local';
    protected static ?string $pluralModelLabel = 'Localizações';
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $slug = 'localizacoes'; // URL /ROTA
    protected static ?string $navigationGroup = 'Endereços';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')->default(auth()->id()),
                Forms\Components\Section::make('Endereço')->description('Endereço da localidade')
                    ->schema([
                        Forms\Components\Select::make('address_id')
                            ->label('Endereço')
                            ->relationship('address', 'building')
                            ->searchable()
                            ->preload()
                            ->optionsLimit(20)
                            ->required(),
                    ]),
                Forms\Components\Section::make('Localidade')->description('Informações da localidade')
                    ->schema([
                        Forms\Components\TextInput::make('sector')
                            ->required()
                            ->label('Setor')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->tel()
                            ->label('Telefone')
                            ->default('Não informado')
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('sector_location')
                            ->required()
                            ->label('Localização do setor')
                            ->default('Não informado')
                            ->maxLength(255)->columnSpan(2),

                    ])->columns(2)




            ]);
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Local')->schema([
                    TextEntry::make('sector')->label('Setor')->icon('heroicon-o-map-pin')->color('primary'),
                    TextEntry::make('phone')->label('Telefone')->badge()->icon('heroicon-o-phone')->color('success'),
                    TextEntry::make('sector_location')->label('Localização do setor'),
                ])->columns(2)
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('address.building')
                    ->label('Prédio')
                    ->color('primary')
                    ->icon('heroicon-o-map')
                    ->url(
                        function (Location $record): string {
                            return 'enderecos/' . $record->address_id;
                        }
                    )
                    ->searchable(),
                Tables\Columns\TextColumn::make('sector')
                    ->label('Setor')
                    ->color('gray')
                    ->icon('heroicon-o-map-pin')

                    ->searchable(),
                Tables\Columns\TextColumn::make('sector_location')
                    ->label('Localização')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Numero')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city')
                    ->label('Cidade')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->label('Estado')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->color('success')
                    ->url(
                        function (TextColumn $colum): string {
                            $state = $colum->getState();
                            return 'tel:' . $state;
                        }
                    )
                    ->icon('heroicon-o-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado-em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado-em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Prédio')
                ->relationship('address', 'building')
                ->searchable()
                ->preload()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            AddressRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'view' => Pages\ViewLocation::route('{record}'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
