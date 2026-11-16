<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosDosAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosDosAprobacion\ModeloDosAprobacionResource;
use App\Models\ModeloDosAprobacion;
use App\Services\Aprobacion\ModeloDosAprobacionContextService;
use App\Services\Aprobacion\ModeloDosAprobacionDocumentService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateModeloDosAprobacion extends CreateRecord
{
    protected static string $resource = ModeloDosAprobacionResource::class;

    private ModeloDosAprobacionContextService $contextService;

    private ModeloDosAprobacionDocumentService $documentService;

    public function boot(
        ModeloDosAprobacionContextService $contextService,
        ModeloDosAprobacionDocumentService $documentService,
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
        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $this->record instanceof ModeloDosAprobacion) {
            $this->documentService->generate($this->record, true);
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
