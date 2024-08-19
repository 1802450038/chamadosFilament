<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComputerResource\Pages;
use App\Filament\Resources\ComputerResource\RelationManagers;
use App\Models\Computer;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComputerResource extends Resource
{
    protected static ?string $model = Computer::class;


    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $modelLabel = 'Computador';
    protected static ?string $pluralModelLabel = 'Computadores';
    // protected static ?string $navigationLabel = 'Computadores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    Forms\Components\TextInput::make('patrimony')
                        ->label('Patrimonio')
                        ->placeholder('Codigo do patrimonio ou alguma identificação')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('brand')
                        ->label('Marca')
                        ->required()
                        ->maxLength(255),
                ])->columns(2),
                Grid::make()->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Imagem')
                        ->image(),
                    Forms\Components\RichEditor::make('description')
                        ->label('Descrição')
                        ->placeholder("Digite a especificação ou alguma informação util, como senha ou se contem alguma peça importante")
                        ->maxLength(255),
                ])->columns(1),
                Forms\Components\Hidden::make('user_id')->default(auth()->id())->label('Registrado por'),
                Forms\Components\Select::make('location_id')
                    ->label('Localização')
                    ->relationship('location', 'sector')
                    ->searchable('sector')
                    ->preload()
                    ->required()
                    ->optionsLimit(20)

            ]);
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
                //
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
            'edit' => Pages\EditComputer::route('/{record}/edit'),
        ];
    }
}
