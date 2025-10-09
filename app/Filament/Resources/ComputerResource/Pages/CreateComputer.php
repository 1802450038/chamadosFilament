<?php

namespace App\Filament\Resources\ComputerResource\Pages;

use App\Filament\Resources\ComputerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ServiceOrderResource;
use App\Models\Computer;
use Filament\Notifications\Notification;

class CreateComputer extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected static string $resource = ComputerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Verifica se já existe um computador com o mesmo patrimônio
        $existingComputer = Computer::where('patrimony', $data['patrimony'])->first();

        if ($existingComputer) {
            // Se o computador já existe, envia uma notificação...
            Notification::make()
                ->title('Patrimônio já cadastrado!')
                ->body('Você está sendo redirecionado para criar uma Ordem de Serviço para este computador.')
                ->warning()
                ->send();

            // ...redireciona para a página de criação de OS, passando o ID do computador...
            $this->redirect(ServiceOrderResource::getUrl('create', ['computer_id' => $existingComputer->id]));

            // ...e interrompe o processo de criação do novo computador.
            $this->halt();
        }

        // Se não encontrou duplicata, apenas retorna os dados para continuar a criação normalmente.
        return $data;
    }

}
