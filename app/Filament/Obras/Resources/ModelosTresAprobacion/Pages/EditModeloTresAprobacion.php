<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosTresAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosTresAprobacion\ModeloTresAprobacionResource;
use App\Filament\Widgets\DocumentoPdfViewerWidget;
use App\Models\ModeloTresAprobacion;
use App\Services\Aprobacion\ModeloTresAprobacionDocumentService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\WidgetConfiguration;

class EditModeloTresAprobacion extends EditRecord
{
    protected static string $resource = ModeloTresAprobacionResource::class;

    private ModeloTresAprobacionDocumentService $documentService;

    public function boot(ModeloTresAprobacionDocumentService $documentService): void
    {
        $this->documentService = $documentService;
    }

    protected function afterSave(): void
    {
        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $this->record instanceof ModeloTresAprobacion) {
            $this->documentService->generate($this->record, true);
        }
    }

    /**
     * @return array<class-string|WidgetConfiguration>
     */
    protected function getFooterWidgets(): array
    {
        if (Filament::getCurrentPanel()?->getId() !== 'ayuntamientos') {
            return [];
        }

        $documentoExpedienteId = $this->generatedDocumentId();

        if ($documentoExpedienteId === null) {
            return [];
        }

        return [
            DocumentoPdfViewerWidget::make([
                'documentoExpedienteId' => $documentoExpedienteId,
            ]),
        ];
    }

    private function generatedDocumentId(): ?int
    {
        $record = $this->record;

        if (! $record instanceof ModeloTresAprobacion) {
            return null;
        }

        return $record->documentoGenerado()?->getKey();
    }
}
