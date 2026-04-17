<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\Enums\TemplateVariableSourceType;
use App\Models\DocumentoGenerico;
use App\Models\DocumentoGenericoVariable;
use App\Services\Templates\TemplateVariableValueResolver;
use Illuminate\Support\Carbon;

/**
 * Sincroniza la definición almacenada de variables con el contenido real de la plantilla.
 *
 * Marca nuevas variables detectadas, conserva configuración previa y desactiva
 * las que hayan dejado de existir en el archivo Word.
 */
final class WordTemplateVariableSynchronizer
{
    public function __construct(
        private readonly TemplatePathResolver $templatePathResolver,
        private readonly WordTemplatePlaceholderExtractor $placeholderExtractor,
        private readonly TemplateVariableValueResolver $valueResolver,
    ) {
    }

    /**
     * Sincroniza y devuelve el número de variables detectadas en la plantilla.
     */
    public function sync(DocumentoGenerico $documento): int
    {
        $templatePath = $this->templatePathResolver->resolveForDocument($documento);
        $variables = $this->placeholderExtractor->extract($templatePath);

        if (! $documento->exists) {
            return count($variables);
        }

        /** @var \Illuminate\Support\Collection<string, DocumentoGenericoVariable> $existing */
        $existing = $documento->variables()->get()->keyBy('variable');

        foreach ($variables as $variable) {
            $current = $existing->get($variable);

            $documento->variables()->updateOrCreate(
                ['variable' => $variable],
                [
                    'source_type' => $current?->source_type ?? TemplateVariableSourceType::AUTO->value,
                    'source_path' => $current?->source_path ?? $this->valueResolver->suggestSourcePath($variable),
                    'default_value' => $current?->default_value,
                    'format' => $current?->format,
                    'is_required' => $current?->is_required ?? true,
                    'is_active' => true,
                    'last_detected_at' => Carbon::now(),
                ],
            );
        }

        if ($variables === []) {
            $documento->variables()->update(['is_active' => false]);

            return 0;
        }

        $documento->variables()
            ->whereNotIn('variable', $variables)
            ->update(['is_active' => false]);

        return count($variables);
    }
}
