<?php

namespace App\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePrinter extends CreateRecord
{

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected static string $resource = PrinterResource::class;
}
