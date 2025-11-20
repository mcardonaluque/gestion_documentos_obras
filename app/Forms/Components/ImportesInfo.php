<?php
namespace App\Forms\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class ImportesInfo extends Fieldset
{
    
    public function setImportesDataOrganismo($Importes): static
    { 
        $i=0;
        $datos = [];
       foreach ($Importes as $Importe){
        $datos[$i]=[
            'organismo' => $Importe->organismos->DESCRIPCION ?? null,
            'Expediente' => $obra->Expediente ?? null,
            'importe_aprobado' => $Importe->importe_aprobado ?? null,
            'Porc_Imp_Aprobado' => $Importe->Porc_Imp_aprobado ?? null,
            'importe_a_contratar' => $Importe->importe_a_contratar?? null,
            'importe_adjudicacion' => $Importe->importe_adjudicacioin ?? null,
            'importe_adremanente' => $Importe->importe_remanente ?? null,
            'importe_baja_contratacion' => $Importe->importe_baja_contratacion ?? null,
            'importe_ejecutado' => $Importe->importe_ejecutado ?? null,
            'importe_ejecutado_decreto' => $Importe->importe_ejecutado_decreto ?? null,
            'Porc_imp_Adjudicado' => $Importe->Porc_imp_adjudicado ?? null,
            'Porc_imp_ejecutado' => $Importe->Porc_imp_ejecutado ?? null,
            'Porc_imp_contratar' => $Importe->Porc_imp_contratar ?? null,
            'Porc_imp_baja' => $Importe->Porc_imp_baja ?? null,
        ];
        $i++;
       }
        return $this->default($datos);
    }
    public function setImportesporOrganismo($Importes): static
    {   $obra=$Importes->obra;
        return $this->default([
            'importe_aprobado' => $Importes->importe_aprobado ?? null,
            'numero_obra' => $obra->numero_obra ?? null,
            'subreferencia' => $obra->subreferencia ?? null,
            'ao_ejecucion' => $obra->ao_ejecucion ?? null,
            'Ubicacion' => $obra->municipios->nombre_municipio ?? $obra->carretera,
            'Zona' => $obra->municipios->municipio->zonas->ZONA ?? null,
            'nombre_obra' => $obra->nombre_obra1?? null,
            'Expediente' => $obra->Expediente ?? null,
            'forma_ejecucion' => $obra->ejecucion->DESCRIPCION ?? null,
            'estado_obra' => $obra->estados->DESCRIPCION ?? null,
        ]);
    }
   
    public function getChildComponents(): array
    {
        return [
            TextInput::make('codigo_plan')
                ->label('Código de Obra')
                ->disabled()
                ->dehydrated(),
                
            TextInput::make('numero_obra')
                ->label('Nombre de la Obra')
                ->disabled()
                ->dehydrated(),
                
            TextInput::make('subreferencia')
                ->label('Subreferencia')
                ->disabled()
                ->dehydrated(),
                
            TextInput::make('ao_ejecucion')
                ->label('Año de Ejecución')
                ->disabled()
                ->dehydrated()
                ->numeric(),
                
            TextInput::make('nombre_obra1')
                ->label('Nombre de la Obra')
                ->disabled()
                ->dehydrated(),
                
            TextInput::make('Ubicacion')
                ->id('Ubicacion')
                ->label('Ubicación')
                ->extraAttributes(['class' => 'custom-textinput-class'])
                //->searchable()
                ->formatStateUsing(function ($record) {
                    // dd($record->municipios);
   
                       return $record->municipios->nombre_municipio ?? $record->carretera;
                   })
                ->disabled() // Hace que el campo sea de solo lectura
                ->dehydrated(false) // Evita que el campo se guarde en la base de datos
                ->visible(fn ($get) => $get('municipio') || $get('carretera')),
                
            TextInput::make('forma_ejecucion')
                ->label('Forma de ejecución')
                ->columnSpan(1)
                //->required()
                ->disabled(),
                
            TextInput::make('Expediente')
                ->label('Expediente')
                ->disabled()
                ->dehydrated()
                ->numeric(),
        ];
    }
}