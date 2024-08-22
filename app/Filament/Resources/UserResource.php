<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use League\Flysystem\Visibility;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('Dados')->description('Informações sobre o usuario')->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('occupation')
                            ->label('Cargo')
                            ->required()
                            ->maxLength(255)
                            ->default('tecnico'),
                        Forms\Components\Hidden::make('password')
                            ->label('Senha')
                            ->required()
                            ->default(env('CITY') . '123'),
                            
                        Forms\Components\Toggle::make('admin')->visible(auth()->user()->admin)
                            ->label('Admin')
                            ->required()
                            ->default(false),
                    ])->columnSpan(1),
                    Forms\Components\Grid::make()->schema([

                        Forms\Components\Section::make('Foto')->description('Foto de perfil do usuario')->schema([
                            Forms\Components\FileUpload::make('picture')
                                ->label('Foto'),
                        ]),
                        Forms\Components\Section::make('Personalização')->description('Preferencias do usuario')->schema([
                            Forms\Components\Grid::make()->schema([
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
                            ])->columns(2),


                        ]),
                    ])->columnSpan(1)
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('picture')
                    ->label('Foto')
                    ->circular()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Data verificação do email')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('occupation')
                    ->label('Cargo')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Ativo')
                    ->toggleable()
                    ->boolean(),
                Tables\Columns\IconColumn::make('admin')
                    ->label('Administrador')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
