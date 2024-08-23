<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;

class EditUserInfo extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
{
    return $this->previousUrl ?? $this->getResource()::getUrl('index');
}

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('Dados')->description('Informações sobre o usuario')->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(40),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('occupation')
                            ->label('Cargo')
                            ->required()
                            ->options([
                                'tecnico' => 'Tecnico',
                                'atendente' => 'Atendente'
                            ])
                            ->default('tecnico'),
                        Forms\Components\Toggle::make('admin')
                            ->label('Admin')
                            ->default(false),
                        Forms\Components\Toggle::make('status')
                            ->label('Ativo')
                            ->default(true),
                    ])->columns(2),
                ])->columns(2),
            ]);
    }
}
