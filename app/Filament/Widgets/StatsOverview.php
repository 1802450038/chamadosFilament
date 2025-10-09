<?php

namespace App\Filament\Widgets;

use App\Models\Call;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

        protected function getColumns(): int
    {
        return 6; // Apenas uma coluna para ocupar toda a largura
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Chamados em Aberto', Call::where('status', true)->count())
                ->description('Chamados que ainda não foram finalizados')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('warning'),
        ];
    }
}