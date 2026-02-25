<?php
namespace App\Forms\Components;

use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use App\Models\DatosDeInicioDeObras;
class ObraGeneralInfo extends Fieldset
{
    private $obra = null;

    protected function resolveObra(): ?DatosDeInicioDeObras
    {
        if ($this->obra) {
            return $this->obra;
        }

        $record = $this->getRecord();

        if (blank($record?->expediente_id)) {
            return null;
        }

        $this->obra = DatosDeInicioDeObras::query()
            ->where('expediente_id', $record->expediente_id)
            ->with(['planes', 'municipios.zonas', 'ejecucion', 'estados'])
            ->first();

        return $this->obra;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(fn (): array => $this->getSchemaComponents());
    }

    public function setObraData($reg): static
    {
       if (blank($reg)) {
           $this->obra = null;

           return $this;
       }

       $this->obra = DatosDeInicioDeObras::query()
           ->where('expediente_id', $reg->expediente_id)
           ->with(['planes', 'municipios.zonas', 'ejecucion', 'estados'])
           ->first();

       if (! $this->obra) {
           return $this;
       }

        return $this->default([
            'Codigo_Plan' => $this->obra->Codigo_Plan ?? null,
            'Plan' => $this->obra->planes?->denominacion_plan,
            'numero_obra' => $this->obra->numero_obra ?? null,
            'subreferencia' => $this->obra->subreferencia ?? null,
            'ao_ejecucion' => $this->obra->ao_ejecucion ?? null,
            'Ubicacion' => $this->obra->municipios?->nombre_municipio ?? $this->obra->carretera,
            'Zona' => $this->obra->municipios?->zonas?->ZONA,
            'nombre_obra1' => $this->obra->nombre_obra1 ?? null,
            'expediente_id' => $this->obra->expediente_id ?? null,
            'forma_ejecucion' => $this->obra->ejecucion?->DEN_CONTRATA,
            'codigo_estado_obra' => $this->obra->codigo_estado_obra ?? null,
            'Estado' => $this->obra->estados?->estado_abrev,
        ]);

    }


    protected function getSchemaComponents(): array
    {
        return [
        Section::make('Información de la obra de la Obra')
            ->columns(3)
            ->columnSpan(3)
            ->schema([
                TextInput::make('Codigo_Plan')
                    ->label('Codigo del Plan')
                    ->formatStateUsing(fn ($state) => $state ?? $this->resolveObra()?->Codigo_Plan)
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
                    ->formatStateUsing(fn ($state) => $state ?? $this->resolveObra()?->numero_obra)
                    ->columnSpan(1)
                    ->disabled(),
                TextInput::make('subreferencia')
                    ->label('Subreferencia')
                    ->formatStateUsing(fn ($state) => $state ?? $this->resolveObra()?->subreferencia)
                    ->columnSpan(1)
                    ->disabled(),
                TextInput::make('ao_ejecucion')
                    ->label('Año de Ejecución')
                    ->formatStateUsing(fn ($state) => $state ?? $this->resolveObra()?->ao_ejecucion)
                    ->columnSpan(1)
                    ->disabled(),
                Placeholder::make('Ubicacion')
                    ->id('Ubicacion')
                    ->label('Ubicación')
                    ->extraAttributes(['class' => 'custom-textinput-class'])
                    //->searchable()
                    ->content(function ($record) {
                        $obra = $this->resolveObra();

                        return $obra?->municipios?->nombre_municipio ?? $obra?->carretera ?? 'Sin ubicación disponible';
                    })
                    ->disabled() // Hace que el campo sea de solo lectura

                    ->dehydrated(false), // Evita que el campo se guarde en la base de datos
                    //->visible(fn ($get) => $get('municipio') || $get('carretera')),
                Placeholder::make('zona')
                    ->id('zona')
                    ->label('Zona')
                    ->content(function ($get, $record) {
                        // Obtener el municipio y su zona
                        $municipio = $this->resolveObra()?->municipios;
                        if ($municipio && $municipio->zonas) {
                            return $municipio->zonas->ZONA;
                        }
                        return 'No disponible';
                    })
                    ->dehydrated(false)
                    ->disabled(),
                Placeholder::make('nombre_obra1')
                    ->label('Nombre de la Obra')
                    ->columnSpan(2)
                    ->content(function ($record) {
                                    return $this->resolveObra()?->nombre_obra1 ?? 'Sin nombre disponible';
                    })
                    ->disabled(),
                TextInput::make('expediente_id')
                    ->label('Expediente')
                    ->columnSpan(2)
                    ->extraAttributes(['class' => 'compact-input w-40'])
                    ->formatStateUsing(fn ($state) => $state ?? $this->resolveObra()?->expediente_id)
                    ->disabled(),
                Placeholder::make('forma_ejecucion')
                    ->label('Forma de Ejecución')
                    ->columnSpan(1)
                    ->content(function ($record) {
                                    return $this->resolveObra()?->forma_ejecucion ?? 'Sin nombre disponible';
                    })
                    ->disabled(),
                Placeholder::make('ejecucion')
                    ->id('ejecucion')
                    ->columnSpan(2)
                    ->extraAttributes([
                        'class' => 'border border-gray-300 rounded-lg p-2 bg-gray-50 shadow-sm'])
                    ->label('Forma de Ejecución')
                    ->content(function ($get, $record) {

                        $ejecucion = $this->resolveObra()?->ejecucion;
                        if ($ejecucion ) {
                            return ucwords($ejecucion->DEN_CONTRATA);
                        }
                        return 'No disponible';
                    })
                    ->dehydrated(false)
                    ->disabled(),
                Placeholder::make('codigo_estado_obra')
                    ->label('Estado de la Obra')
                    ->columnSpan(1)
                    ->content(function ($get, $record) {
                        return ucwords($this->resolveObra()?->codigo_estado_obra);
                    })
                    ->disabled(),


                Placeholder::make('Estado')
                    ->label('Estado')
                    ->content(function ($get, $record) {

                        $estado = $this->resolveObra()?->estados;

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
        Placeholder::make('Obra')
            ->id('obra')
            ->label('Obra')
            ->hiddenLabel()
            ->content(function ($get, $record) {
                return $record->Codigo_Plan . '-' . $record->numero_obra . '-' . $record->subreferecnia . '-' . $record->ao_ejecucion;
            })
            ->disabled() // Hace que el campo sea de solo lectura
            ->dehydrated(false),

        Placeholder::make('Plan')
            ->label('Plan')
            ->columnSpan(2)
            ->content(function ($record) {
                // dd($record->municipios);

                    return $record->planes->denominacion_plan;
                })
            ->dehydrated(false)
            ->disabled(),
            //->extraAttributes(['class' => 'custom-textinput-class']),

        Placeholder::make('Ubicacion')
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
        Placeholder::make('zona')
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
        Placeholder::make('ejecucion')
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
    Placeholder::make('Estado')
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
