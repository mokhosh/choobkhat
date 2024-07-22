<?php

namespace App\Models;

use App\Models\States\Session\Finished;
use App\Models\States\Session\SessionState;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\ModelStates\HasStates;

/**
 * @property SessionState $state
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class);
    }

    public function duration(): Attribute
    {
        return new Attribute(
            get: fn () => $this->end?->diff($this->start),
        );
    }

    public function finish()
    {
        $this->state->transitionTo(Finished::class);
    }
}
