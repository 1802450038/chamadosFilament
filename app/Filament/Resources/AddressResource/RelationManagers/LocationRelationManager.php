<?php

namespace App\Filament\Resources\AddressResource\RelationManagers;

use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LocationRelationManager extends RelationManager
{
    protected static string $relationship = 'location';
    protected static string $formTitle = 'location';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sector')
                    ->label('Setor')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sector_location')
                    ->label('Localização do setor')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->label('Telefone')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('address.building')
                    ->label('Prédio')
                    ->color('primary')
                    ->icon('heroicon-o-map')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sector')
                    ->label('Setor')
                    ->color('gray')
                    ->icon('heroicon-o-map-pin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sector_location')
                    ->label('Localização')
                    ->searchable(),
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
            ])->searchable(false)
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make(
                //     [
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
