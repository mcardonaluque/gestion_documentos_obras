<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

use App\Models\DocumentoExpediente;

/**
 * Ejemplo de uso del trait NotifiesAndValidatesDates.
 *
 * Copia los fragmentos en una Page de Filament (CreateRecord/EditRecord o Action custom).
 */
final class NotifiesAndValidatesDatesUsageExample
{
    public function exampleCreateFlow(array $data): void
    {
        // Ejemplo orientativo (no ejecutable en runtime, solo referencia).
        $record = new DocumentoExpediente();
        $record->fill($data);

        // 1) valida reglas de fecha, 2) guarda, 3) registra ejecucion y notifica.
        // $this->persistWithDateControls($record);

        // Notificar al usuario actual.
        // $this->notifyCurrentUser('Documento creado', 'Se ha creado el documento correctamente.', 'success');

        // Notificar al team del expediente.
        // $teamId = $this->resolveTeamIdFromModel($record);
        // if ($teamId !== null) {
        //     $this->notifyTeam($teamId, 'Nuevo documento', 'Hay un nuevo documento en su expediente.', 'info');
        // }
    }
}
