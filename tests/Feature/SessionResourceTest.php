<?php

use App\Filament\Resources\SessionResource;
use App\Models\User;

use function Pest\Laravel\be;
use function Pest\Laravel\get;

beforeEach(function (): void {
    be($this->user = User::factory()->create());
});

it('returns a successful response', function (): void {
    get(SessionResource::getUrl())
        ->assertStatus(200);
});

it("shows current user's sessions", function (): void {})->todo();
it("does not show other users' sessions", function (): void {})->todo();
it('creates new sessions instantly', function (): void {})->todo();
it('can update sessions', function (): void {})->todo();
