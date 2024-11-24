<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SessionResource;
use App\Filament\Resources\SessionResource\Pages\ListSessions;
use App\Models\Project;
use App\Models\States\Session\Ongoing;
use Carbon\CarbonInterface;
use Filament\Tables\Actions;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Morilog\Jalali\Jalalian;
use Override;

class LatestSessions extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    #[Override]
    public function table(Table $table): Table
    {
        return SessionResource::table($table)
            ->poll('1s')
            ->query(
                auth()->user()->sessions()->getQuery()->take(5)
                    ->when($project = $this->getProject(), fn ($query) => $query->where(
                        fn ($q) => $q
                            ->where('project_id', $project->getKey())
                            ->orWhere('state', Ongoing::class)
                    ))
                    ->where('start', '>=', $this->getStart())
                    ->where(
                        fn ($q) => $q
                            ->where('end', '<=', $this->getEnd())
                            ->orWhereNull('end')
                    )
            )
            ->paginated(false)
            ->headerActions([
                ListSessions::getCreateSessionAction(Actions\Action::class)
                    ->mountUsing(fn (Table $table): Table => $table->poll(null)),
                ...(Project::default() instanceof Project ? [ListSessions::getCreateSessionNowAction(Actions\Action::class)] : []),
            ]);
    }

    protected function getProject(): ?Project
    {
        return $this->filters['project']
            ? Project::query()->find($this->filters['project'])
            : null;
    }

    protected function getStart(): CarbonInterface
    {
        return $this->filters['start']
            ? Carbon::parse($this->filters['start'])->startOfDay()
            : Jalalian::now()->getFirstDayOfMonth()->toCarbon()->startOfDay();
    }

    protected function getEnd(): CarbonInterface
    {
        return $this->filters['end']
            ? Carbon::parse($this->filters['end'])->endOfDay()
            : now();
    }
}
