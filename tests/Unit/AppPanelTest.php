<?php

use Filament\Facades\Filament;

test('there is an app panel', function (): void {
    expect(Filament::getPanel('app'))->not->toBeNull();
});
