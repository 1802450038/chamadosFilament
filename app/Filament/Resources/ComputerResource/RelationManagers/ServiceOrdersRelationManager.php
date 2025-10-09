<?php

namespace App\Filament\Resources\ComputerResource\RelationManagers;

use App\Filament\Resources\ServiceOrderResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'serviceOrders';

    protected static ?string $title = 'Ordens de Serviço';
    protected static ?string $modelLabel = 'Ordem de Serviço';

    public function form(Form $form): Form
    {
        return $form
            // O formulário de criação/edição pode ser mais simples aqui
            // ou podemos reutilizar o do ServiceOrderResource se necessário.
            ->schema([
                Forms\Components\TextInput::make('defect')
                    ->label('Defeito')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('defect')
            ->columns([
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Ativo'),
                Tables\Columns\TextColumn::make('defect')
                    ->label('Defeito')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tecs.name')
                    ->label('Técnicos')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Descomente a linha abaixo para permitir criar uma OS a partir daqui
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('Visualizar')
                    ->url(fn ($record): string => ServiceOrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}