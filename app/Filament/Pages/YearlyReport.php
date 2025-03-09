<?php

namespace App\Filament\Pages;

use App\Models\Month;
use App\Models\Project;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Attributes\Url;

/**
 * @property Form $form
 */
class YearlyReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'tabler-report';

    protected static string $view = 'filament.pages.yearly-report';

    #[Url]
    public ?string $project = null;

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(Month::project($this->project))
            ->heading(Project::find($this->project)?->title ?? 'All Projects')
            ->headerActions([
                Tables\Actions\Action::make('select project')
                    ->form([
                        Select::make('project')
                            ->options(Project::all()->pluck('title', 'id'))
                            ->searchable(),
                    ])
                    ->action(fn ($data) => $this->project = $data['project']),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('month'),
                Tables\Columns\TextColumn::make('time')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('cumulative')
                    ->placeholder('—'),
            ]);
    }
}
