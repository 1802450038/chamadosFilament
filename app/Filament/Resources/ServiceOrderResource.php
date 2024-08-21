<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceOrderResource\Pages;
use App\Filament\Resources\ServiceOrderResource\RelationManagers;
use App\Models\ServiceOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceOrderResource extends Resource
{
    protected static ?string $model = ServiceOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $modelLabel = 'Ordem de serviço';
    protected static ?string $pluralModelLabel = 'Ordens de serviço';
    protected static ?string $slug = 'ordens-servico';
    protected static ?string $navigationGroup = 'Serviços';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Computador')->description('Informações sobre o computador')->schema([
                    Forms\Components\Hidden::make('user_id')->default(auth()->id())
                        ->label('Registrado por'),
                    Forms\Components\Select::make('computer_id')
                        ->relationship('computer', 'patrimony')
                        ->label('Computador')
                        ->required()
                        ->preload()
                        ->optionsLimit(20)
                        ->searchable(),
                ]),
                Forms\Components\Section::make('Os')->description('Informações sobre a ordem de serviço')->schema([
                    Forms\Components\Select::make('tecs')
                    ->label('Tecnicos')
                    ->relationship('tecs', 'name')
                    ->preload()
                    ->multiple()
                    ->maxItems(3),
                Forms\Components\TextInput::make('defect')
                    ->label('Defeito')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('repair_note')
                    ->label('Nota')
                    ->maxLength(255)
                    ->default('Não informado')->columnSpan(2),
                ])->columns(2),

               
            ])->columns(2);
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
                Tables\Columns\TextColumn::make('computer.patrimony')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tecs.name')
                    ->label('Tecnicos')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('defect')
                    ->label('Defeito')
                    ->searchable(),
                Tables\Columns\TextColumn::make('repair_note')
                    ->label('Nota')
                    ->html()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Editado em')
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
            'index' => Pages\ListServiceOrders::route('/'),
            'create' => Pages\CreateServiceOrder::route('/create'),
            'edit' => Pages\EditServiceOrder::route('/{record}/edit'),
        ];
    }
}
