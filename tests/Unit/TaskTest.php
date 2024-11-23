<?php

use App\Models\Project;
use App\Models\Task;

it('has attributes', function (): void {
    Task::factory()->create();

    expect(Task::query()->find(1))
        ->project->toBeInstanceOf(Project::class)
        ->title->not->toBeNull()
        ->notes->not->toBeNull();
});
