<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class TopTechniciansByCallsChart extends ChartWidget
{
    protected static ?string $heading = 'Técnicos com Mais Chamados Atribuídos';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Filtra apenas usuários que são técnicos e conta a relação 'calls'
        $data = User::where('occupation', 'tecnico')
            ->withCount('calls') // Assumindo que a relação no User model se chama 'calls'
            ->orderByDesc('calls_count')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Chamados',
                    'data' => $data->pluck('calls_count')->toArray(),
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FFB1C1',
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}