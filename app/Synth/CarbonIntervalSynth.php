<?php

namespace App\Synth;

use Carbon\CarbonInterval;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;

class CarbonIntervalSynth extends Synth
{
    public static string $key = 'interval';

    public static function match($target): bool
    {
        return $target instanceof CarbonInterval;
    }

    public function dehydrate($target): array
    {
        return [$target->__toString(), []];
    }

    public function hydrate($value): CarbonInterval
    {
        return CarbonInterval::createFromDateString($value);
    }
}
