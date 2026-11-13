<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosUnoAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosUnoAprobacion\ModeloUnoAprobacionResource;
use App\Services\Aprobacion\ModeloUnoAprobacionContextService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateModeloUnoAprobacion extends CreateRecord
{
    protected static string $resource = ModeloUnoAprobacionResource::class;

    private ModeloUnoAprobacionContextService $contextService;

    public function boot(ModeloUnoAprobacionContextService $contextService): void
    {
        $this->contextService = $contextService;
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
}
