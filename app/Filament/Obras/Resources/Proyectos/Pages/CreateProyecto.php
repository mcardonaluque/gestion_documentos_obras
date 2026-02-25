<?php

namespace App\Filament\Obras\Resources\Proyectos\Pages;

use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use App\Models\DatosDeInicioDeObras;
use App\Models\Proyecto;
use Filament\Resources\Pages\CreateRecord;

class CreateProyecto extends CreateRecord
{
    protected static string $resource = ProyectoResource::class;

    public function mount(): void
{
    parent::mount();

    if ($expediente = request()->get('expediente_id')) {

        // Cargar datos de la obra
        $obra = DatosDeInicioDeObras::with([
            'importes',
            'municipios'
        ])->where('expediente_id', $expediente)->first();

        $ultimoNumero = Proyecto::where('CODIGO_MUNICIPIO', $obra->municipio)
        ->where('AO_EJECUCION', $obra->ao_ejecucion) ->max('NUMERO_PROYECTO');
        $nuevoNumero = ($ultimoNumero ?? 0) + 1;
        if ($obra) {
            $this->form->fill([
                'NUMERO_PROYECTO' => $nuevoNumero,
                'AO_EJECUCION=' => $obra->ao_ejecucion,
                'CODIGO_MUNICIPIO' => $obra->municipio,
                'expediente_id' => $obra->expediente_id,
                'Codigo_plan' => $obra->Codigo_plan,
                'numero_obra' => $obra->numero_obra,
                'subreferencia' => $obra->subreferencia,
                'den_proyecto' => $obra->nombre_obra1,
                'importe_proyecto' => $obra->importes->importe_aprobado ?? null,
                'carretera' => $obra->carretera,
                'servicio_gestor' => '590',
            ]);
        }
    }
}

}

