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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')->default(auth()->id())
                    ->label('Registrado por'),
                Forms\Components\Select::make('computer_id')
                    ->relationship('computer', 'patrimony')
                    ->label('Computador')
                    ->required()
                    ->preload()
                    ->optionsLimit(20)
                    ->searchable(),
                Forms\Components\Select::make('tec_1')
                    ->label('Tecnico 1')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(20)
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('tec_2')
                    ->label('Tecnico 2')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(20)
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('tec_3')
                    ->label('Tecnico 3')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(20)
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('defect')
                    ->label('Defeito')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('repair_note')
                    ->label('Nota')
                    ->maxLength(255)
                    ->default('Não informado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Registrado por')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('computer.patrimony')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tec_1')
                    ->label('Usuarios')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(function ($state, ServiceOrder $os) {
                        return (isset($os->tec_1) ? $os->tec($os->tec_1) : '') . " " .
                            (isset($os->tec_2) ? $os->tec($os->tec_2) : '') . " " .
                            (isset($os->tec_3) ? $os->tec($os->tec_3) : '');
                    })
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
