<?php
namespace App\Forms\Components;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\DB;
use App\Models\DatosDeInicioDeObras;
class ObraGeneralInfo extends Fieldset
{    
    private  $obra;
    public function setObraData($reg): static
    {
      //  dd($obra->municipios->municipio->zonas->ZONA);
       $tablaobra='DatosInicioDeObras';
       $this->obra=DatosDeInicioDeObras::where('expediente_id',$reg->expediente_id)->first();
       //dd($this->obra);
        return $this->default([
            'Codigo_Plan' => $this->obra->Codigo_Plan ?? null,
            'Plan' => $this->obra->planes->denominacion_plan ?? null,
            'numero_obra' => $this->obra->numero_obra ?? null,
            'subreferencia' => $this->obra->subreferencia ?? null,
            'ao_ejecucion' => $this->obra->ao_ejecucion ?? null,
            'Ubicacion' => $this->obra ? ($this->obra->municipio->nombre_municipio ?? $this->obra->carretera) : 'Sin obra',
            'Zona' => $this->obra->municipios->municipio->zonas->ZONA ?? null,
            'nombre_obra' => $this->obra->nombre_obra1?? null,
            'Expediente' => $this->obra->expediente_id ?? null,
            'forma_ejecucion' => $this->obra->ejecucion->DESCRIPCION ?? null,
            'estado_obra' => $this->obra->estados->DESCRIPCION ?? null,
        ]);
        
    }
    
   
    public function getChildComponents(): array
    {
        return [
        Section::make('Información de la obra de la Obra')
            ->columns(5)
            ->schema([
        TextInput::make('Codigo_Plan')
            ->label('Codigo del Plan')
            ->columnSpan(1)
            ->disabled(),
        Placeholder::make('Plan')
            ->label('Plan')
            ->content(function ($record) {
                  // dd($record->planes);
                   return $record->planes->denominacion_plan;
               })
            
            ->columnSpan(2)
            ->disabled(),
        TextInput::make('numero_obra')
            ->label('Número de Obra')
            ->columnSpan(1)
            ->disabled(),
        TextInput::make('subreferencia')
            ->label('Subreferencia')
            ->columnSpan(1)
            ->disabled(),
        TextInput::make('ao_ejecucion')
            ->label('Año de Ejecución')
            ->columnSpan(1)
            ->disabled(),
        PlaceHolder::make('Ubicacion')
            ->id('Ubicacion')
            ->label('Ubicación')
            ->extraAttributes(['class' => 'custom-textinput-class'])
            //->searchable()
            ->content(function ($record) {
                // dd( $this->obra->municipios->nombre_municipio );
                return  $this->obra?->municipios?->nombre_municipio ?? $this->obra?->carretera ?? 'Sin ubicación disponible';
               })
            ->disabled() // Hace que el campo sea de solo lectura
            
            ->dehydrated(false), // Evita que el campo se guarde en la base de datos
            //->visible(fn ($get) => $get('municipio') || $get('carretera')),
        PlaceHolder::make('zona')
            ->id('zona')
            ->label('Zona')
            ->content(function ($get, $record) {
                // Obtener el municipio y su zona
                $municipio = $this->obra->municipios;
                if ($municipio && $municipio->zonas) {
                    return $municipio->zonas->ZONA; 
                }
                return 'No disponible';
            })
            ->dehydrated(false)
            ->disabled(),
        PlaceHolder::make('nombre_obra1')
            ->label('Nombre de la Obra')
            ->columnSpan(2)
            ->content(function ($record) {
               // dd( $this->obra->nombre_obra1);
               return  $this->obra?->nombre_obra1 ?? 'Sin nombre disponible';
              })
            ->disabled(),
        TextInput::make('expediente_id')
            ->label('Expediente')
            ->columnSpan(2)
            ->extraAttributes(['class' => 'compact-input w-40'])
            ->disabled(),
        PLaceHolder::make('forma_ejecucion')
            ->label('Forma de Ejecución')
            ->columnSpan(1)
            ->content(function ($record) {
                //dd( $this->obra->municipios->nombre_municipio );
               return  $this->obra?->forma_ejecucion ?? 'Sin nombre disponible';
              })
            ->disabled(),
        PlaceHolder::make('ejecucion')
            ->id('ejecucion')
            ->columnSpan(2)
            ->extraAttributes([
                'class' => 'border border-gray-300 rounded-lg p-2 bg-gray-50 shadow-sm'])
            ->label('Forma de Ejecución')
            ->content(function ($get, $record) {
                
                $ejecucion = $this->obra?->ejecucion;
                if ($ejecucion ) {
                    return ucwords($ejecucion->DEN_CONTRATA); 
                }
                return 'No disponible';
            })
            ->dehydrated(false)
            ->disabled(),
        PlaceHolder::make('codigo_estado_obra')
            ->label('Estado de la Obra')
            ->columnSpan(1)
            ->content(function ($get, $record) {
                return ucwords($this->obra?->codigo_estado_obra); 
            })   
            ->disabled(),
                            

        PlaceHolder::make('Estado')
            ->label('Estado')
            ->content(function ($get, $record) {
                    
                $estado = $this->obra?->estados;
               
                if ($estado ) {
                    return ucwords($estado->estado_abrev); 
                }   
                return 'No disponible';
            })
            //->columnSpan(2)
            //->required()
            ->disabled(),
        ]),       
    
           
 ];  
}
Public function getObraFields(): array
{
    return [
        TextInput::make('Codigo_Plan')
            ->label('Código del Plan')
            ->columnSpan(1)
            ->disabled(),
        TextInput::make('Plan')
            ->label('Plan')
            ->columnSpan(2)
            ->disabled(),
        TextInput::make('numero_obra')
            ->label('Número de Obra')
            ->columnSpan(1)
            ->disabled(),
        TextInput::make('subreferencia')
            ->label('Subreferencia')
            ->columnSpan(1)
            ->disabled(),
        TextInput::make('ao_ejecucion')
            ->label('Año de Ejecución')
            ->columnSpan(1)
            ->disabled(),
        TextInput::make('Ubicacion')
            ->label('Ubicación')
            ->columnSpan(2)
            ->disabled(),
        TextInput::make('Zona')
            ->label('Zona')
            ->columnSpan(1)
            ->disabled(),
        TextInput::make('nombre_obra1')
            ->label('Nombre de la Obra')
            ->columnSpan(5)
            ->disabled(),
        TextInput::make('expediente_id')
            ->label('Expediente')
            ->columnSpan(2)
            ->disabled(),
        TextInput::make('forma_ejecucion')
            ->label('Forma de Ejecución')
            ->columnSpan(2)
            ->disabled(),
        TextInput::make('codigo_estado_obra')
            ->label('Estado de la Obra')
            ->columnSpan(1)
            ->disabled(),
    ];
   }        
 
 public function getFixComponents(): array{
 return[
 
    Section::make('Identificación de la Obra')
    ->columns(5)
    ->schema([
        // Campo virtual "Ubicación"*/
    // Campo para carretera
    TextInput::make('carretera')
        ->label('Carretera')
        ->disabled()
        //->hidden()
        ->visible(fn ($get) => $get('carretera'))
        ->reactive(), // Hace que el campo sea reactivo

    /*Forms\Components\Section::make('Referencia de Obra')
        ->columns(4)
        ->schema([*/
        TextInput::make('Codigo_Plan')
            ->label('Plan')
            //->required()
            ->hidden()
            ->disabled(),
        TextInput::make('numero_obra')
            ->label('número de obra')
            //->required()
            ->hidden()
            ->disabled(),
        TextInput::make('subreferencia')
            //->required()
            ->hidden()
            ->disabled(),
        TextInput::make('ao_ejecucion')
            ->label('año de ejecución')
            ->hidden()
            //->required()
            ->disabled(),
        PlaceHolder::make('Obra')
            ->id('obra')
            ->label('Obra')
            ->hiddenLabel()
            ->content(function ($get, $record) {
                return $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
            })
            ->disabled() // Hace que el campo sea de solo lectura
            ->dehydrated(false),

        PlaceHolder::make('Plan')
            ->label('Plan')
            ->columnSpan(2)
            ->content(function ($record) {
                // dd($record->municipios);

                    return $record->planes->denominacion_plan;
                })
            ->dehydrated(false)
            ->disabled(),
            //->extraAttributes(['class' => 'custom-textinput-class']),
        
        PlaceHolder::make('Ubicacion')
        ->id('Ubicacion')
        ->label('Ubicación')
        ->extraAttributes(['class' => 'custom-textinput-class'])
        //->searchable()
        ->content(function ($record) {
            // dd( $record?->municipios?->nombre_municipio ?? $record?->carretera ?? 'Sin ubicación disponible');

                return  $record?->municipios?->nombre_municipio ?? $record?->carretera ?? 'Sin ubicación disponible';
            })
        ->disabled() // Hace que el campo sea de solo lectura
        
        ->dehydrated(false) // Evita que el campo se guarde en la base de datos
        ->visible(fn ($get) => $get('municipio') || $get('carretera')), // Solo visible si hay un municipio o carretera,
        PlaceHolder::make('zona')
        ->id('zona')
        ->label('Zona')
        ->content(function ($get, $record) {
            // Obtener el municipio y su zona
            $municipio = $record?->municipios;
            if ($municipio && $municipio->zonas) {
                return $municipio->zonas->ZONA; 
            }
            return 'No disponible';
        })
        ->dehydrated(false)
        ->disabled(),
        TextInput::make('nombre_obra1')
            ->label('Nombre de la obra')
            ->columnSpan(2)
            ->disabled(),

        TextInput::make('forma_ejecucion')
            ->label('Forma ejecución')
            ->columnSpan(1)
            //->required()
            ->disabled(),
        PlaceHolder::make('ejecucion')
            ->id('ejecucion')
            ->columnSpan(2)
            ->label('Forma de Ejecución')
            ->content(function ($get, $record) {
                
                $ejecucion = $record?->ejecucion;
                if ($ejecucion ) {
                    return ucwords($ejecucion->DEN_CONTRATA); 
                }
                return 'No disponible';
            })
            ->dehydrated(false)
            ->disabled(),
    PlaceHolder::make('Estado')
            ->label('Estado')
            ->content(function ($get, $record) {
                    
                $estado = $record?->estados;
                if ($estado ) {
                    return ucwords($estado->estado); 
                }   
                return 'No disponible';
            })
            //->columnSpan(2)
            //->required()
            ->disabled(),
        ]),
        ];    
    }
    
}   