<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SessionResource;
use App\Filament\Resources\SessionResource\Pages\ListSessions;
use App\Models\Project;
use Filament\Tables\Actions;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSessions extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $project = $this->filters['project'] ? Project::find($this->filters['project']) : null;

        return SessionResource::table($table)
            ->poll('1s')
            ->query(
                auth()->user()->sessions()->getQuery()->take(5)
                    ->when($project, fn ($query) => $query->where('project_id', $project->getKey()))
            )
            ->paginated(false)
            ->headerActions([
                ListSessions::getCreateSessionAction(Actions\Action::class),
                ListSessions::getCreateSessionNowAction(Actions\Action::class),
            ]);
    }
}
