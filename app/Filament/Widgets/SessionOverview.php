<?php

namespace App\Filament\Widgets;

use App\Models\Session;
use Ariaieboy\Jalali\Jalali;
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
        $start = Jalali::now()->getFirstDayOfMonth()->toCarbon()->startOfDay();
        $end = now();
        $label = sprintf(
            'Working Hours Past %s %s',
            $today = Jalali::now()->getDay(),
            Str::plural('Day', $today),
        );

        return [
            Stat::make(
                label: $label,
                value: Session::query()
                    ->whereBetween('start', [$start, $end])
                    ->get()
                    ->reduce(
                        function ($carry, Session $record) {
                            if ($carry) {
                                return $record->duration->add($carry);
                            }

                            return $record->duration;
                        }
                    ),
            ),
        ];
    }
}
