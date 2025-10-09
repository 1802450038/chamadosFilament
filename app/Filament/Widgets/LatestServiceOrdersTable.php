<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ServiceOrderResource;
use App\Models\ServiceOrder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestServiceOrdersTable extends BaseWidget
{
    protected static ?int $sort = 2; // Para aparecer abaixo da tabela de chamados
    

            protected function getColumns(): int
    {
        return 2; // Apenas uma coluna para ocupar toda a largura
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ServiceOrder::query()->latest()->limit(10)
            )
            ->heading('Últimas 10 Ordens de Serviço')
            ->columns([
                Tables\Columns\TextColumn::make('computer.patrimony')
                    ->label('Patrimônio do Computador')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('defect')
                    ->label('Defeito Reportado')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tecs.name')
                    ->label('Técnicos')
                    ->badge(),

                Tables\Columns\IconColumn::make('active')
                    ->label('Ativa')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado Em')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('Ver OS')
                    ->url(fn (ServiceOrder $record): string => ServiceOrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}