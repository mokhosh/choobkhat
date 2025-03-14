<?php

namespace App\Synth;

use Carbon\CarbonInterval;
use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Override;

class CarbonIntervalSynth extends Synth
{
    public static string $key = 'interval';

    #[Override]
    public static function match($target): bool
    {
        return $target instanceof CarbonInterval;
    }

    public function dehydrate(CarbonInterval $target): array
    {
        return [$target->__toString(), []];
    }

    public function hydrate(string $value): CarbonInterval
    {
        return CarbonInterval::createFromDateString($value);
    }
}
