<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ModelosCeroAprobacion\Pages;

use App\Filament\Obras\Resources\ModelosCeroAprobacion\ModeloCeroAprobacionResource;
use App\Services\Aprobacion\ModeloCeroAprobacionContextService;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

/**
 * Crea un Modelo 0 de aprobación completando el contexto desde su expediente.
 */
class CreateModeloCeroAprobacion extends CreateRecord
{
    protected static string $resource = ModeloCeroAprobacionResource::class;

    private ModeloCeroAprobacionContextService $contextService;

    public function boot(ModeloCeroAprobacionContextService $contextService): void
    {
        $this->contextService = $contextService;
    }

    /**
     * @param  array<string, string|int|float|bool|null>  $data
     * @return array<string, string|int|float|bool|null>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->contextService->enrich($data);
        $tenant = Filament::getTenant();

        if (
            Filament::getCurrentPanel()?->getId() === 'ayuntamientos'
            && $tenant instanceof Model
            && (int) ($data['team_id'] ?? 0) !== (int) $tenant->getKey()
        ) {
            throw ValidationException::withMessages([
                'expediente_id' => 'El expediente seleccionado no pertenece al ayuntamiento activo.',
            ]);
        }

        return $data;
    }
}
