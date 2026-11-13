<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosTresAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosTresAprobacion\ModeloTresAprobacionResource;
use App\Models\ModeloTresAprobacion;
use App\Services\Aprobacion\ModeloTresAprobacionContextService;
use App\Services\Aprobacion\ModeloTresAprobacionDocumentService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateModeloTresAprobacion extends CreateRecord
{
    protected static string $resource = ModeloTresAprobacionResource::class;

    private ModeloTresAprobacionContextService $contextService;

    private ModeloTresAprobacionDocumentService $documentService;

    public function boot(
        ModeloTresAprobacionContextService $contextService,
        ModeloTresAprobacionDocumentService $documentService,
    ): void {
        $this->contextService = $contextService;
        $this->documentService = $documentService;
    }

    /** @param array<string, mixed> $data @return array<string, mixed> */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->contextService->enrich($data);
        $tenant = Filament::getTenant();

        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $tenant instanceof Model
            && (int) $data['team_id'] !== (int) $tenant->getKey()) {
            throw ValidationException::withMessages([
                'expediente_id' => 'El expediente seleccionado no pertenece al ayuntamiento activo.',
            ]);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $this->record instanceof ModeloTresAprobacion) {
            $this->documentService->generate($this->record, true);
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
