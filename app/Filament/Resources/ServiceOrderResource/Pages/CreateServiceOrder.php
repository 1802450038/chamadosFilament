<?php

namespace App\Filament\Resources\ServiceOrderResource\Pages;

use App\Filament\Resources\ServiceOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceOrder extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected static string $resource = ServiceOrderResource::class;
}
