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
     * Genera un PDF desde una plantilla y valores de placeholders ya resueltos.
     *
     * @param array<string, string> $values
     */
    public function downloadPdfFromTemplate(
        string $templatePath,
        array $values,
        string $downloadBaseName,
    ): BinaryFileResponse {
        if (! is_file($templatePath)) {
            throw new RuntimeException(sprintf('No existe la plantilla Word configurada: %s', $templatePath));
        }

        $usesLegacyPlainPlaceholders = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION)) === 'dot';
        $processor = new TemplateProcessor($this->prepareTemplateForProcessor($templatePath));

        $this->replaceTemplateValues($processor, $values, $usesLegacyPlainPlaceholders);

        $docxPath = $this->createTemporaryFilePath('docx');
        $processor->saveAs($docxPath);
        $pdfPath = $this->convertToPdf($docxPath);
        $downloadName = $this->buildDownloadNameFromBase($downloadBaseName, 'pdf');

        return response()->download($pdfPath, $downloadName)->deleteFileAfterSend(true);
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

        $templatePath = $this->prepareTemplateForProcessor(
            $this->templatePathResolver->resolveForDocument($documento),
        );
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
        return $this->convertDocument($docxPath, 'pdf');
    }

    /**
     * Prepara una plantilla para PhpWord, que sólo admite formatos OOXML.
     */
    private function prepareTemplateForProcessor(string $templatePath): string
    {
        if (strtolower(pathinfo($templatePath, PATHINFO_EXTENSION)) !== 'dot') {
            return $templatePath;
        }

        $temporaryDotPath = $this->createTemporaryFilePath('dot');

        if (! copy($templatePath, $temporaryDotPath)) {
            throw new RuntimeException('No se ha podido preparar la plantilla Word .dot para su conversión.');
        }

        try {
            return $this->convertDocument($temporaryDotPath, 'docx');
        } finally {
            @unlink($temporaryDotPath);
        }
    }

    /**
     * Sustituye macros estándar o los placeholders sin delimitadores de las .dot heredadas.
     *
     * @param array<string, string> $values
     */
    private function replaceTemplateValues(
        TemplateProcessor $processor,
        array $values,
        bool $usesLegacyPlainPlaceholders,
    ): void {
        if ($usesLegacyPlainPlaceholders) {
            $processor->setMacroChars('', '');
        }

        try {
            foreach ($values as $variableName => $value) {
                $processor->setValue($variableName, $value);
            }
        } finally {
            if ($usesLegacyPlainPlaceholders) {
                $processor->setMacroChars('${', '}');
            }
        }
    }

    /**
     * Convierte un documento Word a otro formato usando LibreOffice.
     */
    private function convertDocument(string $sourcePath, string $targetExtension): string
    {
        $sofficeBinary = 'C:\\Program Files\\LibreOffice\\program\\soffice.exe';

        if (! is_file($sofficeBinary)) {
            throw new RuntimeException('LibreOffice no está disponible en la ruta esperada para convertir la plantilla a PDF.');
        }

        $outputDirectory = dirname($sourcePath);
        $command = sprintf(
            '"%s" --headless --convert-to %s --outdir "%s" "%s"',
            $sofficeBinary,
            $targetExtension,
            $outputDirectory,
            $sourcePath,
        );

        $output = [];
        $exitCode = 0;

        exec($command, $output, $exitCode);

        $outputPath = $outputDirectory . DIRECTORY_SEPARATOR . pathinfo($sourcePath, PATHINFO_FILENAME) . '.' . $targetExtension;

        if ($exitCode !== 0 || ! is_file($outputPath)) {
            throw new RuntimeException(sprintf('No se ha podido convertir el documento Word a %s.', strtoupper($targetExtension)));
        }

        return $outputPath;
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

    private function buildDownloadNameFromBase(string $baseName, string $extension): string
    {
        $slug = Str::slug($baseName);

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
