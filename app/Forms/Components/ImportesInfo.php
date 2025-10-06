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
        return $this->default([
            'importe_aprobado' => $Importes->importe_aprobado ?? null,
            'numero_obra' => $importes->numero_obra ?? null,
            'subreferencia' => $importes->subreferencia ?? null,
            'ao_ejecucion' => $importes->ao_ejecucion ?? null,
            'organismo' => $Importes->organismos->DESCRIPCION ?? null,
            'Porc_Imp_Aprobado' => $Importes->Porc_Imp_aprobado ?? null,
            'importe_a_contratar' => $Importes->importe_a_contratar?? null,
            'Expediente' => $obra->Expediente ?? null,
            'importe_adjudicacion' => $Importes->importe_adjudicacioin ?? null,
            'importe_adremanente' => $Importes->importe_remanente ?? null,
            'importe_baja_contratacion' => $Importes->importe_baja_contratacion ?? null,
            'importe_ejecutado' => $Importes->importe_ejecutado ?? null,
            'importe_ejecutado_decreto' => $Importes->importe_ejecutado_decreto ?? null,
            'Porc_imp_Adjudicado' => $Importes->Porc_imp_adjudicado ?? null,
            'Porc_imp_ejecutado' => $Importes->Porc_imp_ejecutado ?? null,
            'Porc_imp_contratar' => $Importes->Porc_imp_contratar ?? null,
            'Porc_imp_baja' => $Importes->Porc_imp_baja ?? null,

        ]);
    }
    public function setImportesporOrganismo($Importes): static
    {
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
                ->dehydrated()
                ->rows(3),
                
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