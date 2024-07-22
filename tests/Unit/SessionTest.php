<?php

use App\Models\Session;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

it('has attributes', function () {
    Session::factory()->create();

    expect(Session::query()->find(1))
        ->user->toBeInstanceOf(User::class)
        ->start->not->toBeNull()
        ->end->not->toBeNull()
        ->duration->not->toBeNull();
});

it('belongs to tasks', function () {
    $tasks = Task::factory()->create();
    Session::factory()->recycle($tasks)->create();

    expect(Session::query()->find(1))
        ->tasks->toBeInstanceOf(Collection::class);
});
