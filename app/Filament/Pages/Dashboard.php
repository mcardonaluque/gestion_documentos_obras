<?php
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getTitle(): string
    {
        return 'Panel Administrativo'; // 👈 título en el header
    }

    public  function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\UltimasObrasTableWidget::class,
        ];
    }
}
