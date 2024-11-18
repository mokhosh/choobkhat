<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Session;
use Filament\Support\Colors\Color;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

class SessionOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = '1s';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = -5;

    protected function getColumns(): int
    {
        return 1;
    }

    public function getStats(): array
    {
        $project = $this->filters['project'] ? Project::find($this->filters['project']) : null;
        $start = $this->filters['start'] ? Carbon::parse($this->filters['start'])->startOfDay() : null;
        $end = $this->filters['end'] ? Carbon::parse($this->filters['end'])->endOfDay() : null;
        $label = $this->getLabel($start, $end);

        return [
            Stat::make(
                label: $label,
                value: Session::getWorkingHoursSummary($start, $end, $project),
            )->chart(
                chart: Session::getWorkingHoursChart(project: $project),
            )->chartColor(
                Session::isOngoing() ? Color::Red : Color::Green,
            ),
        ];
    }

    protected function getLabel(?Carbon $start, ?Carbon $end): string
    {
        if (is_null($start) && is_null($end)) {
            return sprintf(
                'Working Hours Past %s %s',
                $today = Jalalian::now()->getDay(),
                Str::plural('Day', $today),
            );
        }

        return sprintf(
            'Working Hours Since %s %s',
            is_null($start) ? 'the start of this month' : Jalalian::fromCarbon($start)
                ->format($start->year === now()->year ? 'n/j' : 'Y/n/j'),
            is_null($end) ? '' : 'Until '.Jalalian::fromCarbon($end)
                ->format($end->year === now()->year ? 'n/j' : 'Y/n/j'),
        );
    }
}
