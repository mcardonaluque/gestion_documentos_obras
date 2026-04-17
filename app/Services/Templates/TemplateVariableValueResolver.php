<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\Enums\TemplateVariableSourceType;
use App\Models\DocumentoGenericoVariable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Resuelve el valor final que debe insertarse en cada variable de plantilla.
 *
 * Soporta reglas automáticas, rutas al modelo actual, valores de sistema y
 * constantes fijas definidas por configuración del documento.
 */
final class TemplateVariableValueResolver
{
    /**
     * Resuelve una variable y la devuelve convertida a texto listo para Word.
     *
     * @param array<string, \DateTimeInterface|array<string, string|int|float|bool|null>|string> $context
     */
    public function resolve(DocumentoGenericoVariable $variable, Model $record, array $context = []): string
    {
        $sourceType = TemplateVariableSourceType::tryFrom((string) $variable->source_type) ?? TemplateVariableSourceType::AUTO;

        $value = match ($sourceType) {
            TemplateVariableSourceType::AUTO => $this->resolveAutomaticValue($variable->variable, $record, $context),
            TemplateVariableSourceType::RECORD => $this->resolveFromRecord($record, $variable->source_path),
            TemplateVariableSourceType::SYSTEM => $this->resolveSystemValue($variable->source_path, $context),
            TemplateVariableSourceType::FIXED => $variable->default_value,
        };

        if ($this->isBlankValue($value)) {
            $value = $variable->default_value;
        }

        return $this->stringifyValue($value, $variable->format);
    }

    /**
     * Sugiere automáticamente una ruta lógica probable a partir del nombre WD_*.
     */
    public function suggestSourcePath(string $variable): ?string
    {
        $normalized = strtoupper(trim($variable));
        $aliases = $this->defaultAliases();

        if (array_key_exists($normalized, $aliases)) {
            return $aliases[$normalized];
        }

        $raw = preg_replace('/^WD_/', '', $normalized);

        if (! is_string($raw) || $raw === '') {
            return null;
        }

        return Str::snake(strtolower($raw));
    }

    /**
     * @param array<string, \DateTimeInterface|array<string, string|int|float|bool|null>|string> $context
     */
    private function resolveAutomaticValue(string $variable, Model $record, array $context): \DateTimeInterface|array|string|int|float|bool|null
    {
        $suggestedPath = $this->suggestSourcePath($variable);

        if (is_string($suggestedPath) && $suggestedPath !== '') {
            if (str_starts_with($suggestedPath, 'system.')) {
                return $this->resolveSystemValue(Str::after($suggestedPath, 'system.'), $context);
            }

            $recordValue = data_get($record, $suggestedPath);

            if (! $this->isBlankValue($recordValue)) {
                return $recordValue;
            }

            $inputValue = data_get($context, 'input.' . $suggestedPath);

            if (! $this->isBlankValue($inputValue)) {
                return $inputValue;
            }
        }

        return data_get($context, 'input.' . strtoupper(trim($variable)));
    }

    private function resolveFromRecord(Model $record, ?string $path): \DateTimeInterface|array|string|int|float|bool|null
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        return data_get($record, trim($path));
    }

    /**
     * @param array<string, \DateTimeInterface|array<string, string|int|float|bool|null>|string> $context
     */
    private function resolveSystemValue(?string $path, array $context): \DateTimeInterface|array|string|int|float|bool|null
    {
        $key = is_string($path) ? trim($path) : '';

        return match ($key) {
            'today', 'fecha_hoy' => now(),
            'year', 'ao', 'ao_actual' => (string) now()->year,
            'month', 'mes' => (string) now()->month,
            'generated_at' => data_get($context, 'generated_at'),
            default => null,
        };
    }

    /**
     * @return array<string, string>
     */
    private function defaultAliases(): array
    {
        return [
            'WD_PLAN' => 'planes.denominacion_plan',
            'WD_DEFIPLAN' => 'planes.denominacion_plan',
            'WD_LOCALIDAD' => 'municipios.nombre_municipio',
            'WD_MUNICIPIO' => 'municipios.nombre_municipio',
            'WD_EXPEDIENTE' => 'expediente_id',
            'WD_OBRA' => 'obra',
            'WD_IMPORTEAPROBADO' => 'importes.importe_aprobado',
            'WD_FORMAEJE' => 'ejecucion.DEN_CONTRATA',
            'WD_FECHA_HOY' => 'system.today',
            'WD_FECHA_ACTUAL' => 'system.today',
        ];
    }

    private function stringifyValue(\DateTimeInterface|array|string|int|float|bool|null $value, ?string $format = null): string
    {
        if ($value instanceof DateTimeInterface) {
            $dateFormat = is_string($format) && str_starts_with($format, 'date:')
                ? Str::after($format, 'date:')
                : 'd/m/Y';

            return $value->format($dateFormat);
        }

        if (is_bool($value)) {
            return $value ? 'SI' : 'NO';
        }

        if (is_numeric($value) && is_string($format) && str_starts_with($format, 'number:')) {
            $decimals = (int) Str::after($format, 'number:');

            return number_format((float) $value, $decimals, ',', '.');
        }

        if (is_array($value)) {
            $items = array_map(static function ($item): string {
                return is_scalar($item) ? (string) $item : '';
            }, $value);

            return implode(', ', array_filter($items, static fn (string $item): bool => $item !== ''));
        }

        if ($value === null) {
            return '';
        }

        return trim((string) $value);
    }

    private function isBlankValue(\DateTimeInterface|array|string|int|float|bool|null $value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_array($value)) {
            return $value === [];
        }

        return false;
    }
}
