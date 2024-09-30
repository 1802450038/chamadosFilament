<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CallRelationManager extends RelationManager
{
    protected static string $relationship = 'calls';

    protected static ?string $title = 'Chamados';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('issue')
                    ->label('Problema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tecs.name')
                    ->label('Tecnicos')
                    ->badge()
                    ->icon('heroicon-m-user')
                    ->searchable(),
                Tables\Columns\TextColumn::make('request')
                    ->label('Solicitante')
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            'Não informado' => 'danger',
                            $state => 'primary'
                        }
                    )
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheduling')
                    ->label('Agendado')
                    ->date()
                    ->badge()
                    ->color(
                        function ($state): string {
                            if (date('d-m-Y', strtotime($state)) < date('d-m-Y', strtotime(now()))) {
                                return 'danger';
                            } elseif (date('d-m-Y', strtotime($state)) > date('d-m-Y', strtotime(now()))) {
                                return 'primary';
                            } elseif (date('d-m-Y', strtotime($state)) == date('d-m-Y', strtotime(now()))) {
                                return 'success';
                            } else {
                                return 'gray';
                            }
                        }
                    )
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Ativo')
                    ->onIcon('heroicon-o-megaphone')
                    ->onColor('success')
                    ->offIcon('heroicon-o-x-mark')
                    ->offColor('gray'),
                Tables\Columns\TextColumn::make('location.sector')
                    ->label('Local')
                    ->color('gray')
                    ->icon('heroicon-o-map-pin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Editado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
