<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;
use Sushi\Sushi;

class Month extends Model
{
    use Sushi;

    public static ?string $project = null;

    public static ?int $year = null;

    protected array $months = [
        'Farvardin', 'Ordibehesht', 'Khordad', 'Tir', 'Mordad', 'Shahrivar',
        'Mehr', 'Aban', 'Azar', 'Dey', 'Bahman', 'Esfand',
    ];

    public static function yearly(?string $project, ?int $year = null): Builder
    {
        static::$year = $year ?? Jalalian::now()->getYear();
        static::$project = $project;

        return static::query();
    }

    public function getRows(): array
    {
        return collect($this->months)->map(function ($month, $key) {
            $startOfYear = new Jalalian(static::$year, 1, 1)->toCarbon()->startOfDay();
            $start = new Jalalian(static::$year, $key + 1, 1)->getFirstDayOfMonth()->toCarbon()->startOfDay();
            $end = new Jalalian(static::$year, $key + 1, 1)->getEndDayOfMonth()->toCarbon()->endOfDay();
            $project = Project::query()->find(static::$project);

            return [
                'month' => $month,
                'time' => Session::getWorkingHoursSummary($start, $end, $project)?->format('%H:%I:%S'),
                'cumulative' => Session::getWorkingHoursSummary($startOfYear, $end, $project)?->format('%H:%I:%S'),
            ];
        })->toArray();
    }
}
