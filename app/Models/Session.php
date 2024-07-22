<?php

namespace App\Models;

use App\Models\States\Session\Finished;
use App\Models\States\Session\SessionState;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'state' => SessionState::class,
    ];

    public static function getStateOptionsArray(): Collection
    {
        return self::getStatesFor('state')
            ->mapWithKeys(fn ($state) => [$state => str($state)->afterLast('\\')]);
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
            get: fn () => $this->start?->diff($this->end),
        );
    }

    public function finish(): void
    {
        $this->state->transitionTo(Finished::class);
    }
}
