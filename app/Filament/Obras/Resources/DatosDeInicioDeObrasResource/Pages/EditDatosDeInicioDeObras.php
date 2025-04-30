<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\Pages;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatosDeInicioDeObras extends EditRecord
{
    protected static string $resource = DatosDeInicioDeObrasResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Obtener el modelo actual
        $record = $this->getRecord();
        //dd($record->ayuda);
        // Calcular el valor de `ubicacion`
        if ($record) {
            $data['Ubicacion'] = $record->municipios ? $record->municipios->nombre_municipio : $record->carretera;
            //$data['Obra']= $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
           if ($data['CompApAyto'] != 'NO'){
            $data['Aportacion'] = 'SI';
            $data['CompApAyto'] = $record->CompApAyto;
           }
        } 
        if ($record && $record->municipios) {
            $record->load('municipios.zonas');
            $data['municipios'] = $record->municipios;
        }
        if ($record && $record->ayuda) {
            // Carga los datos de "ayuda" en el formulario
            $data['ayuda'] = [
                'Expediente' => $record->ayuda->Expediente,
                'dpto_redactor' => $record->ayuda->dpto_redactor,
                'departamento_direccion' => $record->ayuda->departamento_direccion,
                'AyuTecRed' => $record->ayuda->AyuTecRed,
                'AyuTecDir' => $record->ayuda->AyuTecDir,
                'SubvencionEconomicaR' => $record->ayuda->SubvencionEconomicaR,
                'SubvencionEconomicaD' => $record->ayuda->SubvencionEconomicaD,
                'codigo_municipio' => $record->ayuda->codigo_municipio,
            ];
        }
        return $data;
    }
}
