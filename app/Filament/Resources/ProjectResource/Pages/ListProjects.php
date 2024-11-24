<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Override;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(fn ($data) => auth()->user()->projects()->create($data)),
        ];
    }
}
