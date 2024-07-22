<?php

namespace App\Models\States\Session;

use Filament\Support\Contracts\HasColor;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class SessionState extends State implements HasColor
{
    abstract public function getColor(): string|array|null;

    public function getTitle(): string
    {
        return str(static::class)->afterLast('\\');
    }

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Ongoing::class)
            ->allowTransition(Ongoing::class, Finished::class, OngoingToFinished::class);
    }
}
