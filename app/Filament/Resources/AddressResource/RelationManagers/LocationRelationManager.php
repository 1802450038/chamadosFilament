<?php

namespace App\Filament\Resources\AddressResource\RelationManagers;

use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LocationRelationManager extends RelationManager
{
    protected static string $relationship = 'location';
    protected static string $formTitle = 'location';


    protected static ?string $label = 'local';
    protected static ?string $pluralLabel = 'localizações';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->location()->count();
    }

    
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Hidden::make('user_id')->default(auth()->id()),


            Forms\Components\Section::make('Localidade')->description('Informações da localidade')
                ->schema([
                    Forms\Components\TextInput::make('sector')
                        ->required()
                        ->label('Setor')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        // ->required()
                        ->tel()
                        ->label('Telefone')
                        ->default('')
                        ->maxLength(255),
                    Forms\Components\RichEditor::make('sector_location')
                        ->required()
                        ->label('Descrição do local do setor')
                        ->placeholder('Informe a posição, andar, localidade do setor...')
                        // ->default('Não informado')
                        ->maxLength(255)->columnSpan(2),

                ])->columns(2)




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
                    ->html()
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make(
                //     [
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
