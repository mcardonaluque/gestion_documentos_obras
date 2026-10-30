<?php

namespace App\Filament\Obras\Resources\PlanSses\Pages;

use App\Filament\Obras\Resources\PlanSses\PlanSsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de planes de seguridad y salud (sin lógica propia). */
class CreatePlanSs extends CreateRecord
{
    protected static string $resource = PlanSsResource::class;
}
