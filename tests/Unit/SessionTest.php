<?php

use App\Models\User;

it('has attributes', function () {
    $session = Session::factory()->create();

    expect($session)
        ->user->toBeInstanceOf(User::class)
        ->start->not->toBeNull()
        ->end->not->toBeNull()
        ->duration->not->toBeNull()
        // ->tasks->not->toBeNull()
    ;
});
