<?php

namespace App\Filament\Obras\Resources\Proyectos\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

/**
 * Listado de proyectos.
 *
 * Genera la clave de cada fila a partir de la clave compuesta del proyecto
 * (municipio|año|número), coherente con el route binding del recurso.
 */
class ListProyectos extends ListRecords
{
    protected static string $resource = ProyectoResource::class;

    /**
     * Construye la clave de fila 'municipio|año|número' del proyecto.
     */
    public function getTableRecordKey(Model | array $record): string
    {
        if (is_array($record)) {
            $municipio = trim((string) ($record['CODIGO_MUNICIPIO'] ?? ''));
            $aoProyecto = trim((string) ($record['AO_PROYECTO'] ?? ''));
            $numeroProyecto = trim((string) ($record['NUMERO_PROYECTO'] ?? ''));

            return implode('|', [$municipio, $aoProyecto, $numeroProyecto]);
        }

        $municipio = trim((string) ($record->CODIGO_MUNICIPIO ?? ''));
        $aoProyecto = trim((string) ($record->AO_PROYECTO ?? ''));
        $numeroProyecto = trim((string) ($record->NUMERO_PROYECTO ?? ''));

        return implode('|', [$municipio, $aoProyecto, $numeroProyecto]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
