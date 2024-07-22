<?php

namespace App\Filament\Resources\SessionResource\Pages;

use App\Filament\Resources\SessionResource;
use App\Models\Project;
use Filament;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListSessions extends ListRecords
{
    protected static string $resource = SessionResource::class;

    public static function getCreateSessionNowAction($base = Actions\Action::class): Actions\Action|Filament\Tables\Actions\Action
    {
        return $base::make('create now')
            ->action(function () {
                return auth()->user()->sessions()->create([
                    'start' => now(),
                ]);
            });
    }

    public static function getCreateSessionAction($base = Actions\Action::class): Actions\Action|Filament\Tables\Actions\Action
    {
        return $base::make('create')
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
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            static::getCreateSessionAction(),
            static::getCreateSessionNowAction(),
        ];
    }
}
