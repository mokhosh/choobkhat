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
