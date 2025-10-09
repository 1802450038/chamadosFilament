<?php

namespace App\Filament\Widgets;

use App\Models\ServiceOrder;
use App\Models\Call;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OpenServiceOrdersStat extends BaseWidget
{
    protected static ?int $sort = -2; // Um valor alto para aparecer primeiro


    protected function getColumns(): int
    {
        return 2; // Apenas uma coluna para ocupar toda a largura
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Ordens de Serviço em Aberto', ServiceOrder::where('active', true)->count())
                ->description('Serviços que aguardam finalização')
                ->descriptionIcon('heroicon-o-wrench-screwdriver')
                ->color('success'),

                       
            Stat::make('Chamados em Aberto', Call::where('status', true)->count())
                ->description('Chamados que ainda não foram finalizados')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('warning'),
        ];
    }
}