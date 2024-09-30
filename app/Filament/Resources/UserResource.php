<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\CallRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\ServiceordersRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

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
                                'tecnico'=>'Tecnico',
                                'atendente'=>'Atendente'
                            ])
                            ->default('tecnico'),
                        Forms\Components\Hidden::make('password')
                            ->label('Senha')
                            ->required()
                            ->default(env('CITY') . '123'),
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Usuario')->schema([
                    TextEntry::make('name')->label('Nome')->icon('heroicon-o-user')->color('success')->size(TextEntry\TextEntrySize::Large),
                    ImageEntry::make('avatar_url')->label('Foto')->size(80)->circular()
                ])->columns(2),
                Section::make('Dados')->schema([
                    TextEntry::make('email')->label('Email'),
                    TextEntry::make('occupation')->label('Cargo')->badge()->color('primary'),
                    IconEntry::make('status')->label('status')
                    ->color(function(Model $record){
                        if($record->status == '1'){
                            return "success";
                        }else {
                            return "danger";
                        }
                    })
                    ->icon(function(Model $record){
                        if($record->status == '1'){
                            return "heroicon-o-check";
                        }else {
                            return "heroicon-o-x-mark";
                        }
                    }),
                    IconEntry::make('admin')->label('admin')
                    ->color(function(Model $record){
                        if($record->admin == '1'){
                            return "success";
                        }else {
                            return "danger";
                        }
                    })
                    ->icon(function(Model $record){
                        if($record->admin == '1'){
                            return "heroicon-o-check";
                        }else {
                            return "heroicon-o-x-mark";
                        }
                    }),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
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
                    Tables\Columns\ToggleColumn::make('status')
                    ->label('Ativo')
                    ->onIcon('heroicon-o-check')
                    ->onColor('primary')
                    ->offIcon('heroicon-o-x-mark')
                    ->offColor('danger'),
                    Tables\Columns\ToggleColumn::make('admin')
                    ->label('Admin')
                    ->onIcon('heroicon-o-check')
                    ->onColor('primary')
                    ->offIcon('heroicon-o-x-mark')
                    ->offColor('danger'),
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
            CallRelationManager::class,
            ServiceordersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('{record}'),
            'edit' => Pages\EditUserInfo::route('/{record}/edit'),

        ];
    }
}
