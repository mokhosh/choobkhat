<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SessionResource\Pages;
use App\Models\Project;
use App\Models\Session;
use App\Models\States\Session\SessionState;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SessionResource extends Resource
{
    protected static ?string $model = Session::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->createOptionUsing(function (array $data): int {
                        return auth()->user()->projects()->create($data)->getKey();
                    }),
                Forms\Components\Select::make('tasks')
                    ->relationship('tasks', 'title')
                    ->multiple()
                    ->createOptionForm([Forms\Components\TextInput::make('title')])
                    ->createOptionUsing(function (array $data, Forms\Get $get): int {
                        // todo find a way to hide create action if no project is selected
                        return Project::find($get('project_id'))->tasks()->create($data)->getKey();
                    }),
                Forms\Components\Textarea::make('notes'),
            ]);
    }

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
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('project.title')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn (SessionState $state) => $state->getTitle())
                    ->color(fn (SessionState $state) => $state->getColor())
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('finish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->authorize('finish')
                    ->action(function (Session $session) {
                        $session->finish();
                    }),
                Tables\Actions\EditAction::make()->visible(fn ($livewire) => is_a($livewire, Pages\ListSessions::class)),
                Tables\Actions\DeleteAction::make()->visible(fn ($livewire) => is_a($livewire, Pages\ListSessions::class)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(fn ($livewire) => is_a($livewire, Pages\ListSessions::class)),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSessions::route('/'),
            'edit' => Pages\EditSession::route('/{record}/edit'),
        ];
    }
}
