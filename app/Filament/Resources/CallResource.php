<?php

namespace App\Filament\Resources;


use App\Filament\Resources\CallResource\Pages;

use App\Filament\Resources\CallResource\RelationManagers\LocationRelationManager;
use App\Filament\Resources\CallResource\RelationManagers\TecsRelationManager;
use App\Models\Call;
use Filament\Forms;
use Filament\Forms\Form;
// use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CallResource extends Resource
{
    protected static ?string $model = Call::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $modelLabel = 'Chamados';
    protected static ?string $pluralModelLabel = 'Chamados';
    protected static ?string $slug = 'chamados';
    protected static ?string $navigationGroup = 'Serviços';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Chamado')->description('Informações sobre o chamado')->schema([
                    Forms\Components\Hidden::make('user_id')->default(auth()->id())
                        ->label('Registrado por'),
                    Forms\Components\TextInput::make('issue')
                        ->label('Problema')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('request')
                        ->label('Solicitante')
                        ->maxLength(255)
                        ->default('Não informado'),
                    Forms\Components\DatePicker::make('scheduling')
                        ->label('Agendamento')
                        ->native(false)
                        ->default(Date(now())),
                ])->columns(3)->columnSpan(2),


                Forms\Components\Section::make('Local')->description('Informações do local')->schema([
                    Forms\Components\Select::make('location_id')
                        ->label('Local')
                        ->relationship('location', 'sector')
                        ->searchable()
                        ->preload()
                        ->optionsLimit(20)
                        ->required(),
                ])->columnSpan(1),

                Forms\Components\Section::make('Tecnicos')->description('Tecnicos do chamado')->schema([
                    Forms\Components\Select::make('tecs')
                        ->label('Tecnicos')
                        ->relationship('tecs', 'name', fn(Builder $query) => $query->where('status', '=', '1')->where('occupation', '=', 'tecnico'))
                        ->preload()
                        ->multiple()
                        ->maxItems(3)
                ])->columnSpan(1),

            ])->columns(2);
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->headerActions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Chamado')->schema([
                    TextEntry::make('issue')->label('Problema'),
                    TextEntry::make('request')->label('Solicitante'),
                    TextEntry::make('scheduling')->label('Agendamento'),
                    TextEntry::make('status')->label('Ativo'),
                ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LocationRelationManager::class,
            TecsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalls::route('/'),
            'create' => Pages\CreateCall::route('/create'),
            'view' => Pages\ViewCall::route('{record}'),
            'edit' => Pages\EditCall::route('/{record}/edit'),
        ];
    }
}
