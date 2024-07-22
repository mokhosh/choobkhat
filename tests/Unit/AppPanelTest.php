<?php

use Filament\Facades\Filament;

test('that true is true', function () {
    expect(Filament::getPanel('app'))->not->toBeNull();
});
