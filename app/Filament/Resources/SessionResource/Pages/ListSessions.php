<?php

namespace App\Filament\Resources\SessionResource\Pages;

use App\Filament\Resources\SessionResource;
use App\Models\Session;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSessions extends ListRecords
{
    protected static string $resource = SessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->action(function () {
                    Session::query()->create([
                        'start' => now(),
                        'user_id' => auth()->id(),
                    ]);
                }),
        ];
    }
}
