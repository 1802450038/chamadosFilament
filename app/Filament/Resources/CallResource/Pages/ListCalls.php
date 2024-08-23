<?php

namespace App\Filament\Resources\CallResource\Pages;

use App\Filament\Resources\CallResource;
use App\Models\Call;
use Filament\Actions;
use Filament\Resources\Components\Tab;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListCalls extends ListRecords
{
    protected static string $resource = CallResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Abertos' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', '=', '1'))
                ->badge(Call::query()->where('status', '=', '1')->count())
                ->badgeColor('primary'),
            'Concluidos' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', '=', '0'))
                ->badge(Call::query()->where('status', '=', '0')->count())
                ->badgeColor('success')
        ];
    }
}
