<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CallResource;
use App\Models\Call;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OpenCallsTable extends BaseWidget
{
    protected static ?int $sort = 1; // Para aparecer no topo


    protected function getColumns(): int
    {
        return 2; // Apenas uma coluna para ocupar toda a largura
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Aqui definimos a busca no banco de dados
                Call::query()->where('status', true)->latest()
            )
            ->heading('Últimos 10 Chamados em Aberto')
            ->defaultPaginationPageOption(10) // Define o limite de itens
            ->columns([
                Tables\Columns\TextColumn::make('issue')
                    ->label('Problema')
                    ->searchable(),

                Tables\Columns\TextColumn::make('location.sector')
                    ->label('Local')
                    ->icon('heroicon-o-map-pin')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tecs.name')
                    ->label('Técnicos')
                    ->badge(),

                Tables\Columns\TextColumn::make('scheduling')
                    ->label('Agendado Para')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Aberto Em')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                // Adiciona um botão para ver o chamado completo no resource
                Tables\Actions\Action::make('Ver Chamado')
                    ->url(fn(Call $record): string => CallResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }
}
