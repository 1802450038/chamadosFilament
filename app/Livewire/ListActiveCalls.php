<?php

namespace App\Livewire;

use App\Filament\Resources\CallResource;
use App\Models\Call;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ListActiveCalls extends Component implements HasTable
{

    use InteractsWithTable;

    protected static string $resource = CallResource::class;

    public static $label = 'Custom Navigation Label';

    public static ?string $title = 'Chamados';

 
    // public static function makeFilamentTranslatableContentDriver():null{
    //    return  null;
    // }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver{
        return app(TranslatableContentDriver::class);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Call::query()->where('status', '=', '1'))
            ->columns([
                Tables\Columns\TextColumn::make("issue")
                    ->label('Problema'),
                Tables\Columns\TextColumn::make("tecs.name")
                    ->label('Tecnicos')->badge()
                    ->icon('heroicon-m-user'),
                Tables\Columns\TextColumn::make("request")
                    ->label('Solicitante'),
                Tables\Columns\TextColumn::make("scheduling")
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
                    ),
                Tables\Columns\TextColumn::make("location.sector")
                    ->label('Local')
                    ->color('gay')
                    ->icon('heroicon-o-map-pin'),
                Tables\Columns\TextColumn::make("created_at")
                    ->label('Data')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([])
            ->headerActions([])
            ->bulkActions([]);
    }

    public function render() : View
    {
        return view('livewire.list-active-calls');
    }
}
