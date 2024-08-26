<?php

namespace App\Filament\Resources\ComputerResource\Pages;

use App\Filament\Resources\ComputerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComputer extends EditRecord
{
    protected static string $resource = ComputerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
