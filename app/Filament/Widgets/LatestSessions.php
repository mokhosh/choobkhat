<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SessionResource;
use App\Filament\Resources\SessionResource\Pages\ListSessions;
use Filament\Tables\Actions;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSessions extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return SessionResource::table($table)
            ->query(auth()->user()->sessions()->getQuery()->take(5))
            ->paginated(false)
            ->headerActions([
                ListSessions::getCreateSessionAction(Actions\Action::class),
                ListSessions::getCreateSessionNowAction(Actions\Action::class),
            ]);
    }
}
