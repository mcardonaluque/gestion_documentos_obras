<?php
namespace App\Filament\Ayuntamientos\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

/** Panel de inicio (dashboard) del panel de ayuntamientos con título personalizado. */
class Dashboard extends BaseDashboard
{
    /** Título personalizado mostrado en la cabecera del panel. */
    public function getTitle(): string
    {
        return 'Planes de Obras Provinciales'; // 👈 título en el header
    }

  
}
