<?php

namespace App\Providers;

use App\Synth\CarbonIntervalSynth;
use Carbon\CarbonInterval;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
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

        FilamentIcon::register([
            'panels::pages.dashboard.navigation-item' => 'tabler-dashboard',
            'panels::widgets.account.logout-button' => 'tabler-logout',
            'panels::user-menu.logout-button' => 'tabler-logout',
            'panels::user-menu.profile-item' => 'tabler-user-circle',
            'panels::theme-switcher.light-button' => 'tabler-sun-high',
            'panels::theme-switcher.dark-button' => 'tabler-moon',
            'panels::theme-switcher.system-button' => 'tabler-device-desktop',
            'actions::edit-action' => 'tabler-edit',
            'actions::delete-action' => 'tabler-trash',
            'actions::view-action' => 'tabler-eye',
            'forms::components.repeater.actions.delete' => 'tabler-trash',
            'forms::components.repeater.actions.reorder' => 'tabler-arrows-sort',
            'panels::pages.dashboard.actions.filter' => 'tabler-filter',
            'tables::actions.filter' => 'tabler-filter',
            'tables::actions.toggle-columns' => 'tabler-columns-3',
        ]);

        Table::$defaultDateDisplayFormat = 'Y/m/d';

        CarbonInterval::setCascadeFactors([
            'minute' => [60, 'seconds'],
            'hour' => [60, 'minutes'],
        ]);
    }
}
