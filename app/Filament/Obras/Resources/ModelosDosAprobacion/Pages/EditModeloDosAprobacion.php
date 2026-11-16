<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosDosAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosDosAprobacion\ModeloDosAprobacionResource;
use App\Filament\Widgets\DocumentoPdfViewerWidget;
use App\Models\ModeloDosAprobacion;
use App\Services\Aprobacion\ModeloDosAprobacionDocumentService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\WidgetConfiguration;

class EditModeloDosAprobacion extends EditRecord
{
    protected static string $resource = ModeloDosAprobacionResource::class;

    private ModeloDosAprobacionDocumentService $documentService;

    public function boot(ModeloDosAprobacionDocumentService $documentService): void
    {
        $this->documentService = $documentService;
    }

    protected function afterSave(): void
    {
        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $this->record instanceof ModeloDosAprobacion) {
            $this->documentService->generate($this->record, true);
        }
    }

    /** @return array<class-string|WidgetConfiguration> */
    protected function getFooterWidgets(): array
    {
        if (Filament::getCurrentPanel()?->getId() !== 'ayuntamientos') {
            return [];
        }

        $record = $this->record;
        if (! $record instanceof ModeloDosAprobacion || $record->documentoGenerado() === null) {
            return [];
        }

        return [
            DocumentoPdfViewerWidget::make([
                'documentoExpedienteId' => $record->documentoGenerado()->getKey(),
            ]),
        ];
    }
}
