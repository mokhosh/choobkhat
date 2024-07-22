<?php

use App\Filament\Resources\SessionResource;
use App\Models\User;

use function Pest\Laravel\be;
use function Pest\Laravel\get;

beforeEach(function () {
    be($this->user = User::factory()->create());
});

it('returns a successful response', function () {
    get(SessionResource::getUrl())
        ->assertStatus(200);
});

it('shows current user\'s sessions', function () {})->todo();
it('does not show other users\' sessions', function () {})->todo();
it('creates new sessions instantly', function () {})->todo();
it('can update sessions', function () {})->todo();
