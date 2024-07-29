<?php

namespace App\Providers;

use App\Synth\CarbonIntervalSynth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Livewire::propertySynthesizer(CarbonIntervalSynth::class);
    }
}
