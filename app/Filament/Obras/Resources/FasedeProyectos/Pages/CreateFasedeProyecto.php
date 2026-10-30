<?php

namespace App\Filament\Obras\Resources\FasedeProyectos\Pages;

use App\Filament\Obras\Resources\FasedeProyectos\FasedeProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/** Página estándar de creación de fases de proyecto (sin lógica propia). */
class CreateFasedeProyecto extends CreateRecord
{
    protected static string $resource = FasedeProyectoResource::class;
}
