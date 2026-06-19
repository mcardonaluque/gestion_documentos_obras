<?php

declare(strict_types=1);

namespace App\DTOs\Informes;

use App\Enums\InformeExpedientesAgrupacion;

final class InformeExpedientesFiltrosData
{
    public function __construct(
        public readonly ?int $anioDesde,
        public readonly ?int $anioHasta,
        public readonly ?string $codEstado,
        public readonly ?int $teamId,
        public readonly InformeExpedientesAgrupacion $agrupacion,
    ) {
    }

    /**
     * @param array<string, mixed> $input
     */
    public static function fromArray(array $input): self
    {
        $rawCodEstado = $input['cod_estado'] ?? null;
        $codEstado = is_scalar($rawCodEstado) ? trim((string) $rawCodEstado) : null;
        $codEstado = $codEstado === '' ? null : $codEstado;

        $rawAgrupacion = $input['agrupacion'] ?? InformeExpedientesAgrupacion::ESTADO->value;
        $agrupacionValue = is_scalar($rawAgrupacion)
            ? (string) $rawAgrupacion
            : InformeExpedientesAgrupacion::ESTADO->value;

        return new self(
            anioDesde: self::toNullableInt($input['anio_desde'] ?? null),
            anioHasta: self::toNullableInt($input['anio_hasta'] ?? null),
            codEstado: $codEstado,
            teamId: self::toNullableInt($input['team_id'] ?? null),
            agrupacion: InformeExpedientesAgrupacion::from($agrupacionValue),
        );
    }

    /**
     * @return array<string, int|string|null>
     */
    public function toQueryParams(): array
    {
        return [
            'anio_desde' => $this->anioDesde,
            'anio_hasta' => $this->anioHasta,
            'cod_estado' => $this->codEstado,
            'team_id' => $this->teamId,
            'agrupacion' => $this->agrupacion->value,
        ];
    }

    private static function toNullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }
}
