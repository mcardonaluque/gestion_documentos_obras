<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosUnoAprobacion\ModeloUnoAprobacionResource;
use App\Filament\Widgets\DocumentoPdfViewerWidget;
use App\Models\ModeloUnoAprobacion;
use App\Services\Aprobacion\ModeloUnoAprobacionDocumentService;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\WidgetConfiguration;

class EditModeloUnoAprobacion extends EditRecord
{
    protected static string $resource = ModeloUnoAprobacionResource::class;

    private ModeloUnoAprobacionDocumentService $documentService;

    public function boot(ModeloUnoAprobacionDocumentService $documentService): void
    {
        $this->documentService = $documentService;
    }

    /** @return array<int, DeleteAction> */
    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $this->record instanceof ModeloUnoAprobacion) {
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
        if (! $record instanceof ModeloUnoAprobacion || $record->documentoGenerado() === null) {
            return [];
        }

        return [
            DocumentoPdfViewerWidget::make([
                'documentoExpedienteId' => $record->documentoGenerado()->getKey(),
            ]),
        ];
    }
}
