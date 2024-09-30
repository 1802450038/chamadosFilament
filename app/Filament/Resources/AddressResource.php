<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers\LocationRelationManager;
use App\Models\Address;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;
use Cheesegrits\FilamentGoogleMaps\Infolists\MapEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;


    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $modelLabel = 'Endereço';
    protected static ?string $pluralModelLabel = 'Endereços';
    protected static ?string $slug = 'enderecos';
    protected static ?string $navigationGroup = 'Endereços';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Hidden::make('user_id')->default(auth()->id()),
                Forms\Components\Section::make('Endereço')->description('Informações do endereço')->schema([
                    Forms\Components\TextInput::make('building')
                        ->label('Prédio')
                        ->required()
                        ->maxLength(255),
                    Geocomplete::make('address')
                        ->label('Endereço')
                        ->geocodeOnLoad()
                        ->countries(['br']) // restrict autocomplete results to these countries
                        ->maxLength(1024)
                        ->prefix('Selecione:')
                        ->placeholder('Informe seu endereço ou selecione o icone ...')
                        ->geolocate() // add a suffix button which requests and reverse geocodes the device location
                        ->geolocateIcon('heroicon-o-map')
                        ->reactive()
                        ->updateLatLng(),
                    Forms\Components\Hidden::make('lat')
                        ->label('Longitude'),
                    Forms\Components\Hidden::make('lng')
                        ->label('Latitude')
                ])->columns(2),

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
                Section::make("Endereço")->schema([
                    TextEntry::make("building")->label("Prédio")->columnSpanFull(),
                    TextEntry::make("address")->label("Endereço")->columnSpanFull(),
                    MapEntry::make('map')
                        ->label("Mapa")
                        ->defaultZoom(15)
                        ->defaultLocation(
                            function (Model $record) {
                                return $record->getCoords();
                            }
                        )->columnSpanFull(),
                ])->columns(2)->description("Detalhes sobre a localização"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('building')
                    ->label('Prédio')
                    ->icon('heroicon-o-building-office')
                    ->color('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Endereço')
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
            LocationRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'view' => Pages\ViewAddress::route('{record}'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
