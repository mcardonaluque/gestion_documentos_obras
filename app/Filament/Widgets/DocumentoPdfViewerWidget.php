<?php

namespace App\Filament\Widgets;

use App\Models\DocumentoExpediente;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class DocumentoPdfViewerWidget extends Widget
{
    protected string $view = 'filament.widgets.documento-pdf-viewer-widget';

    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public ?DocumentoExpediente $record = null;

    public bool $compact = false;

    public int $refreshIteration = 0;

    public function refreshViewer(): void
    {
        $this->refreshIteration++;

        Notification::make()
            ->title('Visor PDF recargado')
            ->success()
            ->send();
    }

    public function validatePdfSource(): void
    {
        if (! $this->record?->exists) {
            Notification::make()
                ->title('Guarda el documento antes de validar el PDF')
                ->warning()
                ->send();

            return;
        }

        if ($this->record->isPdfUrl()) {
            Notification::make()
                ->title('El documento apunta a una URL externa válida')
                ->body($this->record->pdf_source)
                ->success()
                ->send();

            return;
        }

        $resolvedPath = $this->record->resolvePdfPath();

        if ($resolvedPath) {
            Notification::make()
                ->title('Archivo PDF localizado correctamente')
                ->body($resolvedPath)
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('No se ha podido localizar el PDF')
            ->body('Revisa la ruta física o la URL informada en el documento.')
            ->danger()
            ->send();
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
            'compact' => $this->compact,
            'refreshIteration' => $this->refreshIteration,
            'downloadUrl' => $this->record?->pdf_download_url,
        ];
    }
}
