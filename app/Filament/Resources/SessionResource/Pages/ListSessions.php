<?php

namespace App\Filament\Resources\SessionResource\Pages;

use App\Filament\Resources\SessionResource;
use App\Models\Project;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListSessions extends ListRecords
{
    protected static string $resource = SessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create now')
                ->action(function () {
                    return auth()->user()->sessions()->create([
                        'start' => now(),
                    ]);
                }),
            Actions\Action::make('create')
                ->form([
                    Forms\Components\Select::make('project_id')
                        ->relationship('project', 'title')
                        ->createOptionForm([Forms\Components\TextInput::make('title')])
                        ->createOptionUsing(function (array $data): int {
                            return auth()->user()->projects()->create($data)->getKey();
                        }),
                    Forms\Components\Select::make('tasks')
                        ->relationship('tasks', 'title')
                        ->multiple()
                        ->createOptionForm([Forms\Components\TextInput::make('title')])
                        ->createOptionUsing(function (array $data, Forms\Get $get): int {
                            // todo find a way to hide create action if no project is selected
                            return Project::find($get('project_id'))->tasks()->create($data)->getKey();
                        }),
                    Forms\Components\Textarea::make('notes'),
                ])
                ->action(function (array $data) {
                    return auth()->user()->sessions()->create($data + [
                        'start' => now(),
                    ]);
                }),
        ];
    }
}
