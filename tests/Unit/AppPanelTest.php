<?php

use Filament\Facades\Filament;

test('there is an app panel', function () {
    expect(Filament::getPanel('app'))->not->toBeNull();
});
