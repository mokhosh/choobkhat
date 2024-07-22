<?php

namespace App\Filament\Widgets;

use App\Models\Session;
use Ariaieboy\Jalali\Jalali;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SessionOverview extends BaseWidget
{
    public function getStats(): array
    {
        $start = Jalali::now()->getFirstDayOfMonth()->toCarbon()->startOfDay();
        $end = now();

        return [
            Stat::make(
                label: 'Monthly Time',
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
