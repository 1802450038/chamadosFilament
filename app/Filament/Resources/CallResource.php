<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CallResource\Pages;
use App\Models\Call;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Support\Facades\Date;

class CallResource extends Resource
{
    protected static ?string $model = Call::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $modelLabel = 'Chamados';
    protected static ?string $pluralModelLabel = 'Chamados';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->default(Date(now())),
                Forms\Components\Select::make('location_id')
                    ->label('Local')
                    ->relationship('location', 'sector')
                    ->searchable()
                    ->preload()
                    ->optionsLimit(20)
                    ->required(),
                Forms\Components\Select::make('tecs')
                    ->label('Tecnicos')
                    ->relationship('tecs', 'name')
                    ->preload()
                    ->multiple()
                    ->maxItems(3)
            ]);
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalls::route('/'),
            'create' => Pages\CreateCall::route('/create'),
            'edit' => Pages\EditCall::route('/{record}/edit'),
        ];
    }
}
