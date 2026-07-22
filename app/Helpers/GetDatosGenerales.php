<?php

namespace App\Helpers;

use App\Models\DatosDeInicioDeObras;
use App\Models\Expediente;

class GetDatosGenerales
{
    protected static array $obraCache = [];

    protected static array $expedienteCache = [];

    public static function getData(string $table, string $data): mixed
    {
        return match (strtolower(trim($table))) {
            'obra', 'obras', 'datosdeiniciodeobras' => static::getDatosObra($data),
            'expediente', 'expedientes' => static::getDatosExpediente($data),
            'generales', 'general' => static::getDatosGenerales($data),
            default => null,
        };
    }

    /**
     * @return array<string, mixed>
     */
    public static function getDatosObra(?string $expedienteId): array
    {
        if (blank($expedienteId)) {
            return [];
        }

        if (! array_key_exists($expedienteId, static::$obraCache)) {
            $obra = DatosDeInicioDeObras::query()
                ->where('expediente_id', $expedienteId)
                ->with(['municipios', 'estados', 'planes', 'ejecucion'])
                ->first();

            static::$obraCache[$expedienteId] = $obra ? [
                'expediente_id' => $obra->expediente_id,
                'Codigo_Plan' => $obra->Codigo_Plan ?? null,
                'plan_denominacion' => $obra->planes?->denominacion_plan ?? null,
                'referencia' => $obra->numero_obra ?? $obra->referencia ?? null,
                'numero_obra' => $obra->numero_obra ?? $obra->referencia ?? null,
                'subreferencia' => $obra->subreferencia ?? $obra->subreferecnia ?? null,
                'subreferecnia' => $obra->subreferecnia ?? $obra->subreferencia ?? null,
                'ao_ejecucion' => $obra->ao_ejecucion ?? null,
                'municipio' => $obra->municipio ?? null,
                'municipio_nombre' => static::firstFilledValue([
                    $obra->municipios?->nombre_municipio,
                    $obra->municipios?->Municipio,
                    $obra->municipios?->municipio,
                ]),
                'codigo_estado_obra' => $obra->codigo_estado_obra ?? null,
                'estado_nombre' => static::firstFilledValue([
                    $obra->estados?->estado,
                    $obra->estados?->estado_abrev,
                ]),
                'forma_ejecucion' => $obra->forma_ejecucion ?? null,
                'forma_ejecucion_nombre' => static::firstFilledValue([
                    $obra->ejecucion?->DEN_CONTRATA,
                    $obra->ejecucion?->DENOMINACION,
                    $obra->ejecucion?->descripcion,
                ]),
                'team_id' => $obra->team_id ?? null,
            ] : [];
        }

        return static::$obraCache[$expedienteId];
    }

    /**
     * @return array<string, mixed>
     */
    public static function getDatosExpediente(?string $expedienteId): array
    {
        if (blank($expedienteId)) {
            return [];
        }

        if (! array_key_exists($expedienteId, static::$expedienteCache)) {
            $expediente = Expediente::query()
                ->where('expediente_id', $expedienteId)
                ->with(['municipios', 'estados', 'planes', 'ejecucion'])
                ->first();

            static::$expedienteCache[$expedienteId] = $expediente ? [
                'expediente_id' => $expediente->expediente_id,
                'expediente_codigo_plan' => $expediente->Codigo_Plan ?? $expediente->codigo_plan ?? null,
                'expediente_plan_denominacion' => $expediente->planes?->denominacion_plan ?? null,
                'expediente_municipio' => $expediente->municipio ?? null,
                'expediente_municipio_nombre' => static::firstFilledValue([
                    $expediente->municipios?->nombre_municipio,
                    $expediente->municipios?->Municipio,
                    $expediente->municipios?->municipio,
                ]),
                'expediente_estado' => $expediente->cod_estado ?? null,
                'expediente_estado_nombre' => static::firstFilledValue([
                    $expediente->estados?->estado,
                    $expediente->estados?->estado_abrev,
                ]),
                'expediente_forma_ejecucion' => $expediente->forma_ejecucion ?? null,
                'expediente_forma_ejecucion_nombre' => static::firstFilledValue([
                    $expediente->ejecucion?->DEN_CONTRATA,
                    $expediente->ejecucion?->DENOMINACION,
                    $expediente->ejecucion?->descripcion,
                ]),
                'team_id' => $expediente->team_id ?? null,
            ] : [];
        }

        return static::$expedienteCache[$expedienteId];
    }

    /**
     * @return array<string, mixed>
     */
    public static function getDatosGenerales(?string $expedienteId): array
    {
        return array_merge(
            static::getDatosExpediente($expedienteId),
            static::getDatosObra($expedienteId),
        );
    }

    /**
     * @param array<int, mixed> $values
     */
    protected static function firstFilledValue(array $values): mixed
    {
        foreach ($values as $value) {
            if (filled($value)) {
                return $value;
            }
        }

        return null;
    }
}
