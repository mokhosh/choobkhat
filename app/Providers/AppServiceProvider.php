<?php

namespace App\Providers;

use App\Synth\CarbonIntervalSynth;
use Carbon\CarbonInterval;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
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
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn () => view('expiration-behavior-script'),
        );

        CarbonInterval::setCascadeFactors([
            'minute' => [60, 'seconds'],
            'hour' => [60, 'minutes'],
        ]);
    }
}
