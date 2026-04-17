<?php

declare(strict_types=1);

namespace App\Services\Templates;

use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Extrae los placeholders definidos en una plantilla Word.
 *
 * Normaliza los nombres a mayúsculas para simplificar la posterior resolución
 * y sincronización con la tabla de variables configurables.
 */
final class WordTemplatePlaceholderExtractor
{
    /**
     * @return list<string>
     */
    public function extract(string $templatePath): array
    {
        $processor = new TemplateProcessor($templatePath);

        /** @var list<string> $variables */
        $variables = $processor->getVariables();

        $sanitized = [];

        foreach ($variables as $variable) {
            $name = strtoupper(trim($variable));

            if ($name !== '') {
                $sanitized[] = $name;
            }
        }

        $unique = array_values(array_unique($sanitized));
        sort($unique);

        return $unique;
    }
}
