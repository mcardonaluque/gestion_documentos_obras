<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\DTOs\Templates\TemplateRenderResult;
use App\Enums\TemplateVariableSourceType;
use App\Models\DocumentoGenerico;
use App\Models\DocumentoGenericoVariable;
use App\Services\Templates\TemplateContextFactory;
use App\Services\Templates\TemplatePathResolver;
use App\Services\Templates\TemplateVariableValueResolver;
use App\Services\Templates\WordTemplatePlaceholderExtractor;
use App\Services\Templates\WordTemplateVariableSynchronizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Servicio principal de impresión documental desde plantillas Word.
 *
 * Orquesta el ciclo completo de:
 * - localizar la plantilla
 * - detectar y sincronizar variables
 * - resolver valores desde el modelo actual
 * - generar DOCX temporal
 * - convertir opcionalmente el resultado final a PDF
 */
final class WordTemplatePrintService
{
    public function __construct(
        private readonly TemplatePathResolver $templatePathResolver,
        private readonly WordTemplatePlaceholderExtractor $placeholderExtractor,
        private readonly TemplateContextFactory $contextFactory,
        private readonly TemplateVariableValueResolver $valueResolver,
        private readonly WordTemplateVariableSynchronizer $variableSynchronizer,
    ) {
    }

    /**
     * Genera el documento y devuelve una respuesta de descarga en formato DOCX.
     *
     * @param array<string, string|int|float|bool|null> $input
     */
    public function downloadDocx(
        DocumentoGenerico $documento,
        Model $record,
        array $input = [],
        ?string $downloadBaseName = null,
    ): BinaryFileResponse {
        $result = $this->render($documento, $record, $input, 'docx', $downloadBaseName);

        return response()->download($result->outputPath, $result->downloadName)->deleteFileAfterSend(true);
    }

    /**
     * Genera el documento final y lo entrega como PDF descargable.
     *
     * @param array<string, string|int|float|bool|null> $input
     */
    public function downloadPdf(
        DocumentoGenerico $documento,
        Model $record,
        array $input = [],
        ?string $downloadBaseName = null,
    ): BinaryFileResponse {
        $result = $this->render($documento, $record, $input, 'pdf', $downloadBaseName);

        return response()->download($result->outputPath, $result->downloadName)->deleteFileAfterSend(true);
    }

    /**
     * Renderiza una plantilla Word a un archivo temporal de salida.
     *
     * @param array<string, string|int|float|bool|null> $input
     */
    public function render(
        DocumentoGenerico $documento,
        Model $record,
        array $input = [],
        string $format = 'docx',
        ?string $downloadBaseName = null,
    ): TemplateRenderResult {
        if ($documento->exists) {
            $this->variableSynchronizer->sync($documento);
        }

        $templatePath = $this->templatePathResolver->resolveForDocument($documento);
        $context = $this->contextFactory->build($record, $input);
        $processor = new TemplateProcessor($templatePath);

        $variables = $this->placeholderExtractor->extract($templatePath);

        /** @var Collection<string, DocumentoGenericoVariable> $definitions */
        $definitions = $documento->exists
            ? $documento->variables()->where('is_active', true)->get()->keyBy('variable')
            : new Collection();

        foreach ($variables as $variableName) {
            $definition = $definitions->get($variableName);

            if (! $definition instanceof DocumentoGenericoVariable) {
                $definition = new DocumentoGenericoVariable([
                    'variable' => $variableName,
                    'source_type' => TemplateVariableSourceType::AUTO->value,
                    'source_path' => $this->valueResolver->suggestSourcePath($variableName),
                    'is_required' => true,
                    'is_active' => true,
                ]);
            }

            $value = $this->valueResolver->resolve($definition, $record, $context);
            $processor->setValue($variableName, $value);
        }

        $docxPath = $this->createTemporaryFilePath('docx');
        $processor->saveAs($docxPath);

        if ($format === 'pdf') {
            $pdfPath = $this->convertToPdf($docxPath);

            return new TemplateRenderResult(
                $pdfPath,
                $this->buildDownloadName($documento, $downloadBaseName, 'pdf'),
                'pdf',
                $variables,
            );
        }

        return new TemplateRenderResult(
            $docxPath,
            $this->buildDownloadName($documento, $downloadBaseName, 'docx'),
            'docx',
            $variables,
        );
    }

    /**
     * Convierte un DOCX temporal a PDF usando LibreOffice en modo headless.
     */
    private function convertToPdf(string $docxPath): string
    {
        $sofficeBinary = 'C:\\Program Files\\LibreOffice\\program\\soffice.exe';

        if (! is_file($sofficeBinary)) {
            throw new RuntimeException('LibreOffice no está disponible en la ruta esperada para convertir la plantilla a PDF.');
        }

        $outputDirectory = dirname($docxPath);
        $command = sprintf(
            '"%s" --headless --convert-to pdf --outdir "%s" "%s"',
            $sofficeBinary,
            $outputDirectory,
            $docxPath,
        );

        $output = [];
        $exitCode = 0;

        exec($command, $output, $exitCode);

        $pdfPath = (string) preg_replace('/\.docx$/i', '.pdf', $docxPath);

        if ($exitCode !== 0 || ! is_file($pdfPath)) {
            throw new RuntimeException('No se ha podido convertir el documento Word a PDF.');
        }

        return $pdfPath;
    }

    private function buildDownloadName(DocumentoGenerico $documento, ?string $baseName, string $extension): string
    {
        $name = $baseName;

        if (! is_string($name) || trim($name) === '') {
            $name = (string) $documento->getAttribute('nombre');
        }

        $slug = Str::slug($name);

        if ($slug === '') {
            $slug = 'documento';
        }

        return $slug . '.' . $extension;
    }

    private function createTemporaryFilePath(string $extension): string
    {
        $temporaryBase = tempnam(sys_get_temp_dir(), 'tpl_');

        if ($temporaryBase === false) {
            throw new RuntimeException('No se ha podido crear un archivo temporal para la plantilla.');
        }

        $targetPath = $temporaryBase . '.' . $extension;

        if (! rename($temporaryBase, $targetPath)) {
            throw new RuntimeException('No se ha podido preparar el archivo temporal de salida.');
        }

        return $targetPath;
    }
}
