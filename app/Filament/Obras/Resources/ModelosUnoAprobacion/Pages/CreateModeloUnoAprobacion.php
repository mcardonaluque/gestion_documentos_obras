<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosUnoAprobacion\ModeloUnoAprobacionResource;
use App\Models\ModeloUnoAprobacion;
use App\Services\Aprobacion\ModeloUnoAprobacionContextService;
use App\Services\Aprobacion\ModeloUnoAprobacionDocumentService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateModeloUnoAprobacion extends CreateRecord
{
    protected static string $resource = ModeloUnoAprobacionResource::class;

    private ModeloUnoAprobacionContextService $contextService;

    private ModeloUnoAprobacionDocumentService $documentService;

    public function boot(
        ModeloUnoAprobacionContextService $contextService,
        ModeloUnoAprobacionDocumentService $documentService,
    ): void {
        $this->contextService = $contextService;
        $this->documentService = $documentService;
    }

    /** @param array<string, mixed> $data @return array<string, mixed> */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $expedienteId = trim((string) ($data['expediente_id'] ?? ''));
        if (ModeloUnoAprobacion::query()->where('expediente_id', $expedienteId)->exists()) {
            throw ValidationException::withMessages([
                'expediente_id' => 'Ya existe un Modelo 1 de aprobación para este expediente. Edite el registro existente.',
            ]);
        }

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
        if (Filament::getCurrentPanel()?->getId() === 'ayuntamientos' && $this->record instanceof ModeloUnoAprobacion) {
            $this->documentService->generate($this->record, true);
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
