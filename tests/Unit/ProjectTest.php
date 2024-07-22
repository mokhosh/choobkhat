<?php

use App\Models\Project;
use App\Models\User;

it('has attributes', function () {
    Project::factory()->create();

    expect(Project::query()->find(1))
        ->user->toBeInstanceOf(User::class)
        ->title->not->toBeNull()
        ->notes->not->toBeNull();
});
