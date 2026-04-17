<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\Models\DocumentoGenerico;
use RuntimeException;

/**
 * Resuelve la ruta física de una plantilla documental a partir de su definición.
 *
 * Acepta rutas absolutas o relativas y prueba varias ubicaciones candidatas
 * para facilitar el uso desde almacenamiento local o catálogo documental.
 */
final class TemplatePathResolver
{
    /**
     * Devuelve la primera ruta válida encontrada para el documento indicado.
     */
    public function resolveForDocument(DocumentoGenerico $documento): string
    {
        /** @var array<int, string> $candidates */
        $candidates = array_values(array_filter([
            $this->normalizeCandidate($documento->getAttribute('ruta_plantilla')),
            $this->normalizeCandidate($documento->getAttribute('rutaplantilla')),
            $this->normalizeCandidate($documento->getAttribute('plantilla')),
        ]));

        foreach ($candidates as $candidate) {
            $resolved = $this->resolvePath($candidate);

            if ($resolved !== null) {
                return $resolved;
            }
        }

        throw new RuntimeException('No se ha encontrado una plantilla válida para el documento seleccionado.');
    }

    /**
     * Intenta localizar un archivo real a partir de una ruta configurada.
     */
    public function resolvePath(string $path): ?string
    {
        $trimmedPath = trim($path);

        if ($trimmedPath === '') {
            return null;
        }

        $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $trimmedPath);

        /** @var array<int, string> $candidates */
        $candidates = [$normalizedPath];

        if (! $this->isAbsolutePath($normalizedPath)) {
            $relativePath = ltrim($normalizedPath, '\\/');
            $candidates[] = storage_path('app' . DIRECTORY_SEPARATOR . $relativePath);
            $candidates[] = storage_path($relativePath);
            $candidates[] = base_path($relativePath);
        }

        foreach (array_unique($candidates) as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function normalizeCandidate(string|int|float|bool|null $value): ?string
    {
        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }

        return null;
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR)
            || preg_match('/^[A-Za-z]:\\\\/', $path) === 1;
    }
}
