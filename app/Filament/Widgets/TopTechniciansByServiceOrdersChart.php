<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class TopTechniciansByServiceOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Técnicos com Mais Ordens de Serviço';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = User::where('occupation', 'tecnico')
            ->withCount('serviceorders') // Assumindo que a relação no User model se chama 'serviceorders'
            ->orderByDesc('serviceorders_count')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Ordens de Serviço',
                    'data' => $data->pluck('serviceorders_count')->toArray(),
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#A3E4D7',
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Vamos variar, este será um gráfico de pizza
    }
}