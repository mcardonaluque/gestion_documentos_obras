<?php
namespace App\Filament\Ayuntamientos\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getTitle(): string
    {
        return 'Planes de Obras Provinciales'; // 👈 título en el header
    }

  
}
