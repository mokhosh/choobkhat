<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SessionResource\Pages;
use App\Models\Project;
use App\Models\Session;
use App\Models\States\Session\SessionState;
use Carbon\CarbonInterval;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;

class SessionResource extends Resource
{
    protected static ?string $model = Session::class;

    protected static ?string $navigationIcon = 'tabler-stopwatch';

    #[Override]
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('start')
                    ->required(),
                Forms\Components\DateTimePicker::make('end'),
                Forms\Components\Select::make('state')
                    ->options(Session::getStateOptionsArray()),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'title')
                    ->createOptionForm([Forms\Components\TextInput::make('title')])
                    ->createOptionUsing(fn (array $data): int => auth()->user()->projects()->create($data)->getKey()),
                Forms\Components\Select::make('tasks')
                    ->relationship('tasks', 'title')
                    ->multiple()
                    ->createOptionForm([Forms\Components\TextInput::make('title')])
                    ->createOptionUsing(fn (array $data, Forms\Get $get): int =>
                        // todo find a way to hide create action if no project is selected
                        Project::find($get('project_id'))->tasks()->create($data)->getKey()),
                Forms\Components\Textarea::make('notes'),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('start', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->getStateUsing(fn (Session $record): string => $record->start)
                    ->jalaliDate(),
                Tables\Columns\TextColumn::make('start')->time(),
                Tables\Columns\TextColumn::make('end')->time(),
                Tables\Columns\TextColumn::make('duration')
                    ->summarize(Sum::make()
                        ->formatStateUsing(fn ($state): CarbonInterval => CarbonInterval::createFromFormat('s', $state)->cascade())
                        ->label('Total Closed Time')
                    )
                    ->fontFamily(FontFamily::Mono)
                    ->alignEnd()
                    ->grow(),
                Tables\Columns\TextColumn::make('project.title')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn (SessionState $state): string => $state->getTitle())
                    ->color(fn (SessionState $state): string|array|null => $state->getColor())
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project')
                    ->relationship('project', 'title'),
                Tables\Filters\Filter::make('since')
                    ->form([DatePicker::make('since')->jalali()])
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['since'],
                        fn (Builder $query, $since) => $query->where('start', '>=', $since)
                    )),
                Tables\Filters\Filter::make('until')
                    ->form([DatePicker::make('until')->jalali()])
                    ->query(fn (Builder $query, array $data) => $query->when(
                        $data['until'],
                        fn (Builder $query, $until) => $query->where('end', '<=', $until)
                    )),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\Action::make('finish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->authorize('finish')
                    ->action(function (Session $session): void {
                        $session->finish();
                    }),
                Tables\Actions\EditAction::make()->visible(fn ($livewire): bool => is_a($livewire, Pages\ListSessions::class)),
                Tables\Actions\DeleteAction::make()->visible(fn ($livewire): bool => is_a($livewire, Pages\ListSessions::class)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(fn ($livewire): bool => is_a($livewire, Pages\ListSessions::class)),
                ]),
            ]);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSessions::route('/'),
            'edit' => Pages\EditSession::route('/{record}/edit'),
        ];
    }
}
