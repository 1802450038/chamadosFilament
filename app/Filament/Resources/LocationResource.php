<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;


    protected static ?string $modelLabel = 'Local';
    protected static ?string $pluralModelLabel = 'Localizações';
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    // protected static ?string $navigationLabel = 'Localizações';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('building')
                    ->required()
                    ->label('Prédio')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sector')
                    ->required()
                    ->label('Setor')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sector-location')
                    ->required()
                    ->label('Localização do setor')
                    ->maxLength(255),
                Forms\Components\TextInput::make('road')
                    ->required()
                    ->label('Rua')
                    ->maxLength(255),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->label('Numero')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->label('Cidade')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->label('Estado')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->label('Telefone')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('building')
                    ->label('Prédio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sector')
                    ->label('Setor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sector-location')
                    ->label('Localização')
                    ->searchable(),
                Tables\Columns\TextColumn::make('road')
                    ->label('Rua')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Numero')
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
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
