<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SessionResource;
use App\Filament\Resources\SessionResource\Pages\ListSessions;
use App\Models\Project;
use Filament\Tables\Actions;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Morilog\Jalali\Jalalian;

class LatestSessions extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $project = $this->filters['project'] ? Project::find($this->filters['project']) : null;
        $start = $this->filters['start'] ? Carbon::parse($this->filters['start']) : Jalalian::now()->getFirstDayOfMonth()->toCarbon()->startOfDay();
        $end = $this->filters['end'] ? Carbon::parse($this->filters['end']) : now();

        return SessionResource::table($table)
            ->poll('1s')
            ->query(
                auth()->user()->sessions()->getQuery()->take(5)
                    ->when($project, fn ($query) => $query->where('project_id', $project->getKey()))
                    ->when($start, fn ($query) => $query->where('start', '>=', $start))
                    ->when($end, fn ($query) => $query->where(
                        fn ($q) => $q
                            ->where('end', '<=', $end)
                            ->orWhereNull('end')
                    ))
            )
            ->paginated(false)
            ->headerActions([
                ListSessions::getCreateSessionAction(Actions\Action::class),
                ListSessions::getCreateSessionNowAction(Actions\Action::class),
            ]);
    }
}
