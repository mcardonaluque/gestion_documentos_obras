<?php

declare(strict_types=1);

namespace App\DTOs\Templates;

/**
 * Identifica una plantilla registrada en el catálogo documental heredado.
 */
final readonly class DefinicionPlantillaWordHeredada
{
    public function __construct(
        public int $procedureCode,
        public int $applicationNumber,
        public int $modelNumber,
        public string $name,
        public ?string $rutaDestinoModelo,
    ) {
    }
}
