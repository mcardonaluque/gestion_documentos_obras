<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ObrasMenu extends Widget
{
    protected string $view = 'filament.widgets.obras-menu';
    protected static ?int $sort = -1; // aparece arriba del contenido
}
