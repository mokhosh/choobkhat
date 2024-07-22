<?php

use App\Models\Session;
use App\Models\User;

it('has attributes', function () {
    Session::factory()->create();

    expect(Session::find(1))
        ->user->toBeInstanceOf(User::class)
        ->start->not->toBeNull()
        ->end->not->toBeNull()
        ->duration->not->toBeNull()
        // ->tasks->not->toBeNull()
    ;
});
