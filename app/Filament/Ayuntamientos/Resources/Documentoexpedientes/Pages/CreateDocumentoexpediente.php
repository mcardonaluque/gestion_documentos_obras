<?php

namespace App\Filament\Ayuntamientos\Resources\Documentoexpedientes\Pages;

use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use App\Filament\Support\Concerns\NotifiesAndValidatesDates;
use App\Models\DocumentoExpediente;
use App\Models\Subsanacion;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Página de creación de documentos de expediente (panel de ayuntamientos).
 *
 * Usa el trait NotifiesAndValidatesDates para validar fechas y notificar,
 * y completa los datos por defecto del expediente antes de crear.
 */
class CreateDocumentoexpediente extends CreateRecord
{
    use NotifiesAndValidatesDates;

    protected static string $resource = DocumentoexpedienteResource::class;

    private ?int $subsanacionId = null;

    /** Completa los datos con los valores por defecto del expediente antes de crear. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->subsanacionId = filled($data['subsanacion_id'] ?? null)
            ? (int) $data['subsanacion_id']
            : null;
        unset($data['subsanacion_id']);

        return DocumentoExpediente::applyExpedienteDefaults($data);
    }

    /**
     * Crea el documento y resuelve, dentro de la misma transacción, la solicitud seleccionada.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::connection('Obras')->transaction(function () use ($data): DocumentoExpediente {
            $subsanacion = null;

            if ($this->subsanacionId !== null) {
                $subsanacion = Subsanacion::query()
                    ->pendientes()
                    ->whereKey($this->subsanacionId)
                    ->where('expediente_id', $data['expediente_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $subsanacion instanceof Subsanacion) {
                    throw ValidationException::withMessages([
                        'subsanacion_id' => 'La solicitud seleccionada ya no está pendiente o no pertenece al expediente.',
                    ]);
                }
            }

            $documento = DocumentoExpediente::query()->create($data);

            if ($subsanacion instanceof Subsanacion) {
                $subsanacion->forceFill([
                    'documento_subsanacion_id' => $documento->getKey(),
                    'fecha_subsanacion' => now()->toDateString(),
                ])->save();
            }

            return $documento;
        });
    }
}
