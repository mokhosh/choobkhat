<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Session;
use Ariaieboy\Jalali\Jalali;
use Filament\Support\Colors\Color;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Str;

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

        $label = sprintf(
            'Working Hours Past %s %s',
            $today = Jalali::now()->getDay(),
            Str::plural('Day', $today),
        );

        return [
            Stat::make(
                label: $label,
                value: Session::getWorkingHoursSummary(project: $project),
            )->chart(
                chart: Session::getWorkingHoursChart(project: $project),
            )->chartColor(
                Session::isOngoing(project: $project) ? Color::Red : Color::Green,
            ),
        ];
    }
}
