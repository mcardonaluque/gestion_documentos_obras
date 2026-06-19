<?php
namespace App\Forms\Components;

use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;

class ImportesInfo extends Fieldset
{
    protected array $importesData = [];

    protected function ensureImportesData(): void
    {
        if (filled($this->importesData)) {
            return;
        }

        $record = $this->getRecord();

        if (! is_object($record) || ! method_exists($record, 'importes')) {
            return;
        }

        $importe = $record->importes()->first();

        if (! $importe) {
            return;
        }

        $this->importesData = [
            'importe_aprobado' => $importe?->importe_aprobado ?? null,
            'importe_a_contratar' => $importe?->importe_a_contratar ?? null,
            'importe_remanente' =>$importe?->importe_remanente ?? null,
            'importe_adjudicacion' => $importe?->importe_adjudicacion ?? null,
            'importe_baja_contratacion' => $importe?->importe_baja_contratacion ?? null,
            'importe_ejecutado' => $importe?->importe_ejecutado ?? null,
            'importe_ejecutado_decreto' => $importe?->importe_ejecutado_decreto ?? null,
            'importePenalidadesProrrogas' => $importe->importePenalidadesProrrogas ?? null,

        ];
    }

    protected function getImporteValue(string $key, $state)
    {
        if (filled($state)) {
            return $state;
        }

        $this->ensureImportesData();

        return $this->importesData[$key] ?? null;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->schema(fn (): array => $this->getSchemaComponents());
    }

    public function setImportesDataOrganismo($Importes): static
    {
        if (blank($Importes)) {
            return $this;
        }

        $importe = collect($Importes)->first();

        if (! $importe) {
            return $this;
        }

        $this->importesData = [
             'importe_aprobado' => $importe?->importe_aprobado ?? null,
            'importe_a_contratar' => $importe?->importe_a_contratar ?? null,
            'importe_remanente' =>$importe?->importe_remanente ?? null,
            'importe_adjudicacion' => $importe?->importe_adjudicacion ?? null,
            'importe_baja_contratacion' => $importe?->importe_baja_contratacion ?? null,
            'importe_ejecutado' => $importe?->importe_ejecutado ?? null,
            'importe_ejecutado_decreto' => $importe?->importe_ejecutado_decreto ?? null,
            'importePenalidadesProrrogas' => $importe->importePenalidadesProrrogas ?? null,
        ];

        return $this->default($this->importesData);
    }
    public function setImportesDeObra($Importes): static
    {
        if (blank($Importes)) {
            return $this;
        }

        $this->importesData = [
            'importe_aprobado' => $Importes?->importe_aprobado ?? null,
            'importe_a_contratar' => $Importes?->importe_a_contratar ?? null,
            'importe_remanente' =>$Importes?->importe_remanente ?? null,
            'importe_adjudicacion' => $Importes?->importe_adjudicacion ?? null,
            'importe_baja_contratacion' => $Importes?->importe_baja_contratacion ?? null,
            'importe_ejecutado' => $Importes?->importe_ejecutado ?? null,
            'importe_ejecutado_decreto' => $Importes?->importe_ejecutado_decreto ?? null,
            'importePenalidadesProrrogas' => $Importes->importePenalidadesProrrogas ?? null,
        ];

        return $this->default($this->importesData);
    }

    protected function getSchemaComponents(): array
    {
        return [
            TextInput::make('importe_aprobado')
                ->label('Importe Aprobado')
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importe_aprobado', $state))
                ->disabled()
                ->dehydrated(),

            TextInput::make('importe_a_contratar')
                ->label('Importe a Contratar')
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importe_a_contratar', $state))
                ->disabled()
                ->dehydrated(),

            TextInput::make('importe_remanente')
                ->label('Importe Remanente')
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importe_remanente', $state))
                ->disabled()
                ->dehydrated(),

            TextInput::make('importe_adjudicacion')
                ->label('Importe Adjudicación')
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importe_adjudicacion', $state))
                ->disabled()
                ->dehydrated()
                ->numeric(),

            TextInput::make('importe_baja_contratacion')
                ->label('Importe Baja Contratación')
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importe_baja_contratacion', $state))
                ->disabled()
                ->dehydrated(),

            TextInput::make('importe_ejecutado')
                ->id('importe_ejecutado')
                ->label('Importe Ejecutado')
                ->extraAttributes(['class' => 'custom-textinput-class'])
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importe_ejecutado', $state))
                ->disabled() // Hace que el campo sea de solo lectura
                ->dehydrated(false), // Evita que el campo se guarde en la base de datos

            TextInput::make('importe_ejecutado_decreto')
                ->label('Importe Ejecutado Decreto')
                ->columnSpan(1)
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importe_ejecutado_decreto', $state))
                //->required()
                ->disabled(),

            TextInput::make('importePenalidadesProrrogas')
                ->label('Importe Penalidades Prórrogas')
                ->formatStateUsing(fn ($state) => $this->getImporteValue('importePenalidadesProrrogas', $state))
                ->disabled()
                ->dehydrated(),
        ];
    }
}
