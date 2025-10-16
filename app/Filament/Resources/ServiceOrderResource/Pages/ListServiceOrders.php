<?php

namespace App\Filament\Resources\ServiceOrderResource\Pages;

use App\Filament\Resources\ServiceOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\ServiceOrder;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListServiceOrders extends ListRecords
{
    protected static string $resource = ServiceOrderResource::class;

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
                ->modifyQueryUsing(fn(Builder $query) => $query->where('active', '=', '1'))
                ->badge(ServiceOrder::query()->where('active', '=', '1')->count())
                ->badgeColor('primary')->icon('heroicon-o-check-circle'),
            'Finalizados' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('active', '=', '0'))
                ->badge(ServiceOrder::query()->where('active', '=', '0')->count())
                ->badgeColor('success')->icon('heroicon-o-x-mark')
        ];
    }
}
