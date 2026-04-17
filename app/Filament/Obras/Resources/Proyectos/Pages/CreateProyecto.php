<?php

namespace App\Filament\Obras\Resources\Proyectos\Pages;

use App\Filament\Obras\Resources\Proyectos\ProyectoResource;
use App\Models\DatosDeInicioDeObras;
use App\Models\PorcentajesProyectos;
use App\Models\Proyecto;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateProyecto extends CreateRecord
{
    protected static string $resource = ProyectoResource::class;

    public function mount(): void
{
    parent::mount();

    if ($expediente = request()->get('expediente_id')) {
        $codigoMunicipio = request()->get('codigo_municipio');
        $aoEjecucion = request()->get('ao_ejecucion');
        $codigoPlan = request()->get('codigo_plan');
        $numeroObra = request()->get('numero_obra');
        $subreferencia = request()->get('subreferencia');

        // Cargar datos de la obra
        $obra = DatosDeInicioDeObras::with([
            'importes',
            'municipios'
        ])->where('expediente_id', $expediente)->first();

        if (! $obra) {
            return;
        }

        $ultimoNumero = Proyecto::where('CODIGO_MUNICIPIO', $obra->municipio)
        ->where('AO_EJECUCION', $obra->ao_ejecucion) ->max('NUMERO_PROYECTO');
        $nuevoNumero = ($ultimoNumero ?? 0) + 1;

        $data = [
            'NUMERO_PROYECTO' => $nuevoNumero,
            'AO_PROYECTO' => $aoEjecucion ?? $obra->ao_ejecucion,
            'ao_ejecucion' => $aoEjecucion ?? $obra->ao_ejecucion,
            'CODIGO_MUNICIPIO' => $codigoMunicipio ?? $obra->municipio,
            'expediente_id' => $obra->expediente_id,
            'Codigo_Plan' => $codigoPlan ?? $obra->Codigo_plan,
            'referencia' => $numeroObra ?? $obra->numero_obra,
            'numero_obra' => $numeroObra ?? $obra->numero_obra,
            'subreferencia' => $subreferencia ?? $obra->subreferencia,
            'den_proyecto' => $obra->nombre_obra1,
            'importe_proyecto' => $obra->importes->importe_aprobado ?? null,
            'carretera' => $obra->carretera,
            'servicio_gestor' => '590',
            'ajuste_importes' => false,
        ];

        $defaults = PorcentajesProyectos::defaultsForAoProyecto($data['AO_PROYECTO'] ?? null);
        $data = array_merge($data, $defaults);

        try {
            $data = ProyectoResource::prepareFinancialDataBeforeSave($data);
        } catch (ValidationException) {
            // During initial fill, keep defaults even if financial validation is not yet satisfiable.
        }

        $this->form->fill($data);
    }
}

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return ProyectoResource::prepareFinancialDataBeforeSave($data);
    }

}

