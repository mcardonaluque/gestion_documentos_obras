<?php

namespace App\Filament\Obras\Resources\DatosDeInicioDeObrasResource\Pages;

use App\Filament\Obras\Resources\DatosDeInicioDeObrasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDatosDeInicioDeObras extends CreateRecord
{
    protected static string $resource = DatosDeInicioDeObrasResource::class;

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
            'Expediente' => $record->ayuda->expediente_id,
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
protected function mutateFormDataBeforeSave(array $data): array
{
    // Si necesitas manipular los datos antes de guardarlos, hazlo aquí
   
   // dd($data);
          
    if (isset($data['ayuda']) && is_array($data['ayuda'])) {
        $ayudaData = $data['ayuda'];
        unset($data['ayuda']);
        $this->guardarAyudaTecnica($ayudaData);
    }
    $data['peticion_ayuda_tec']= $ayudaData['AyuTecRed'] ==='SI' || $ayudaData['AyuTecDir']==='SI' ? 'SI':'NO';
   
    return $data;

}
private function guardarAyudaTecnica(array $ayudaData): void
{
    // Obtén el registro actual
    $record = $this->getRecord();

    // Si el registro ya tiene una relación "ayuda", actualízala
    if ($record->ayuda) {
        $record->ayuda->update($ayudaData);
        //dd($ayudaData);
    } else {
        // Si no existe la relación, créala
        $record->ayuda()->create($ayudaData);
    }
}
}