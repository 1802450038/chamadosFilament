<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;

class CustomProfileComponent extends Component implements HasForms
{
    use InteractsWithForms;
    use HasSort;

    public ?array $data = [];

    protected static int $sort = 0;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Preferencias do usuário')
                    ->aside()
                    ->description('Ajuste de preferencias do usuario')
                    ->schema([
                        Forms\Components\Radio::make('theme')
                            ->label('Tema')
                            ->boolean()
                            ->default(1)
                            ->options([
                                '1' => 'Claro',
                                '0' => 'Escuro',
                            ])
                            ->descriptions([
                                '1' => 'Tema claro.',
                                '0' => 'Tema escuro.',
                            ])->inline()
                            ->inlineLabel(false),
                        Forms\Components\Select::make('color')
                            ->label('Cor do painel')
                            ->required()
                            ->options(
                                [
                                    'BLUE' => 'AZUL',
                                    'RED' => 'VERMELHO',
                                    'GREEN' => 'VERDE',
                                    'PINK' => 'ROSA',
                                    'ORANGE' => 'LARANJA'
                                ]
                            ),
                    ]),
            ])
            // ->statePath('data')
            ;
    }

    public function save(): void
    {
        $data = $this->form->getState();
    }

    public function render(): View
    {
        return view('livewire.custom-profile-component');
    }
}
