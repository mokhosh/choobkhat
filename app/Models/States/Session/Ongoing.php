<?php

namespace App\Models\States\Session;

use Filament\Support\Contracts\HasColor;

class Ongoing extends SessionState implements HasColor
{
    public function getColor(): string|array|null
    {
        return 'orange';
    }
}
