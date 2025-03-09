<?php

namespace App\Models;

use App\Models\States\Session\Finished;
use App\Models\States\Session\Ongoing;
use App\Models\States\Session\SessionState;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;
use Spatie\ModelStates\HasStates;

/**
 * @property SessionState $state
 * @property Carbon $start
 * @property Carbon $end
 * @property CarbonInterval $duration
 */
class Session extends Model
{
    use HasFactory;
    use HasStates;

    protected $table = 'work_sessions';

    protected $appends = [
        'duration',
    ];

    public static function getStateOptionsArray(): Collection
    {
        return self::getStatesFor('state')
            ->mapWithKeys(fn ($state) => [$state => str($state)->afterLast('\\')]);
    }

    public static function getWorkingHoursSummary(?Carbon $start = null, ?Carbon $end = null, ?Project $project = null): string
    {
        $start ??= Jalalian::now()->getFirstDayOfMonth()->toCarbon()->startOfDay();
        $end ??= now();

        return self::query()
            ->whereBetween('start', [$start, $end])
            ->when($project, fn ($query) => $query->where('project_id', $project->getKey()))
            ->get()
            ->reduce(static::durationReducer(...)) ?? '—';
    }

    public static function getWorkingHoursChart(int $count = 10, ?Project $project = null): array
    {
        return Cache::remember('workingHoursChart'.$project?->getKey(), now()->addDay(), fn () => self::query()
            ->latest('start')
            ->take($count)
            ->when($project, fn ($query) => $query->where('project_id', $project->getKey()))
            ->get()
            ->pluck('duration')
            ->map(fn (CarbonInterval $duration) => $duration->totalSeconds)
            ->toArray());
    }

    public static function isOngoing(?Project $project = null): bool
    {
        return self::whereState('state', Ongoing::class)
            ->when($project, fn ($query) => $query->where('project_id', $project->getKey()))
            ->exists();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    public function duration(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value
                ? CarbonInterval::createFromFormat('s', $value)->cascade() // for closed sessions
                : $this->start->diff($this->end), // for open sessions
        );
    }

    public function finish(): void
    {
        $this->state->transitionTo(Finished::class);
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime',
            'state' => SessionState::class,
        ];
    }

    public static function durationReducer(?CarbonInterval $carry, Session $record): CarbonInterval
    {
        if ($carry instanceof CarbonInterval) {
            return $record->duration->add($carry)->cascade();
        }

        return $record->duration;
    }
}
