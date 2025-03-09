<?php

namespace App\Filament\Exports;

use App\Models\Month;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MonthExporter extends Exporter
{
    protected static ?string $model = Month::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('month'),
            ExportColumn::make('time'),
            ExportColumn::make('cumulative'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = sprintf(
            'Your yearly report export has completed and %s %s exported.',
            number_format($export->successful_rows),
            str('row')->plural($export->successful_rows)
        );

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= sprintf(
                ' %s %s failed to export.',
                number_format($failedRowsCount),
                str('row')->plural($failedRowsCount)
            );
        }

        return $body;
    }
}
