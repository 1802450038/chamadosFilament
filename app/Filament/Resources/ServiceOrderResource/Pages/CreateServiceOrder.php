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

    public function mount(): void
    {
        parent::mount();

        // Verifica se o parâmetro computer_id está presente na URL
        $computerId = request()->query('computer_id');
        if ($computerId) {
            // Define o valor padrão do campo computer_id no formulário
            $this->form->fill([
                'computer_id' => $computerId,
                'user_id' => auth()->id(), 
            ]);
        }
    }
}
