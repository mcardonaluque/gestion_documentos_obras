<?php

namespace App\Filament\Obras\Resources\ObrasPendientesProyecto\Pages;
use App\Filament\Obras\Resources\ObrasPendientesProyecto;
use App\Filament\Obras\Resources\ObrasPendientesProyecto\ObrasPendientesProyectoResource;
use App\Models\DatosDeInicioDeObras;
use Filament\Resources\Pages\CreateRecord;

class CreateObrasPendientesProyecto extends CreateRecord
{
    protected static string $resource = ObrasPendientesProyectoResource::class;
    public function mount(): void
{
    parent::mount();

    if ($expediente = request()->get('expediente_id')) {

        // Cargar datos de la obra
        $obra = DatosDeInicioDeObras::with([
            'importes',
            'municipios'

        ])->where('expediente_id', $expediente)->first();

        if ($obra) {
            $this->form->fill([
                'expediente_id' => $obra->expediente_id,
                'Codigo_plan' => $obra->Codigo_plan,
                'numero_obra' => $obra->numero_obra,
                'subreferencia' => $obra->subreferencia,
                'ao_ejecucion' => $obra->ao_ejecucion,
                'den_proyecto' => $obra->nombre_obra1,
                'importe_gastos_generales' => $obra->importes->importe_aprobado ?? null,
                'municipio' => $obra->municipios->nombre_municipio ?? null,
                'carretera' => $obra->carretera,
                'servicio_gestor' => $obra->servicioGestor->nombre_servicio ?? null,
            ]);
        }
    }
}

}
