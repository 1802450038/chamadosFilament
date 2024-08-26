<?php

namespace App\Filament\Resources\CallResource\Pages;

use App\Filament\Resources\CallResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCall extends CreateRecord
{

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected static string $resource = CallResource::class;
}
