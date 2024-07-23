<?php

namespace App\Filament\Widgets;

use App\Models\Session;
use Ariaieboy\Jalali\Jalali;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Str;

class SessionOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '1s';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = -5;

    protected function getColumns(): int
    {
        return 1;
    }

    public function getStats(): array
    {
        $label = sprintf(
            'Working Hours Past %s %s',
            $today = Jalali::now()->getDay(),
            Str::plural('Day', $today),
        );

        return [
            Stat::make(
                label: $label,
                value: Session::getWorkingHoursSummary(),
            )->chart(
                chart: Session::getWorkingHoursChart(),
            )->chartColor(
                Session::isOngoing() ? Color::Red : Color::Green,
            ),
        ];
    }
}
