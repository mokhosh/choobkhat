<?php

namespace App\Filament\Pages;

use App\Filament\Exports\MonthExporter;
use App\Models\Month;
use App\Models\Project;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Url;
use Morilog\Jalali\Jalalian;

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

    #[Url]
    public ?int $year = null;

    public function table(Table $table): Table
    {
        return $table
            ->poll('1s')
            ->paginated(false)
            ->query(Month::yearly($this->project, $this->year))
            ->heading(sprintf(
                '%s - %s',
                (Project::find($this->project)?->title ?? 'All Projects'),
                $this->year ?? 'This year',
            ))
            ->headerActions([
                Tables\Actions\Action::make('Select project')
                    ->form([
                        Select::make('project')
                            ->options(Project::all()->pluck('title', 'id'))
                            ->searchable(),
                    ])
                    ->action(fn ($data) => $this->project = $data['project']),
                Tables\Actions\Action::make('Select year')
                    ->form([
                        TextInput::make('year')
                            ->numeric()
                            ->default(Jalalian::now()->getYear()),
                    ])
                    ->action(fn ($data) => $this->year = $data['year']),
                ExportAction::make()
                    ->label('Export report')
                    ->exporter(MonthExporter::class)
                    ->formats([ExportFormat::Xlsx]),
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
