<?php

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)->in('.');

// Expectations

expect()->extend('toBeOne', fn () => $this->toBe(1));

// Helpers

function something(): void
{
    // ..
}
