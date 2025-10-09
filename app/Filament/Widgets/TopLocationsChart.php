<?php

namespace App\Filament\Widgets;

use App\Models\Call;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopLocationsChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Locais com Mais Chamados';
    protected static ?int $sort = 2; // Para ordenar os widgets na dashboard

    protected function getData(): array
    {
        $data = Call::query()
            ->select('location_id', DB::raw('COUNT(*) as count'))
            ->groupBy('location_id')
            ->orderByDesc('count')
            ->limit(10)
            ->with('location') // Carrega a relação para pegar o nome do setor
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Chamados',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $data->pluck('location.sector')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}