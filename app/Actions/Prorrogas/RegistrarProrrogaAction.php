<?php

declare(strict_types=1);

namespace App\Actions\Prorrogas;

use App\Enums\ProrrogaTipo;
use App\Models\DatosEjecucionObras;
use App\Models\PlazoObraActivo;
use App\Models\Prorroga;
use App\Services\Prorrogas\ProrrogaRulesService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Registra una prórroga y actualiza los plazos activos afectados.
 *
 * La acción encapsula la secuencia completa de negocio:
 * 1. Calcula la secuencia NumSec.
 * 2. Valida la admisibilidad contra el plazo activo.
 * 3. Persiste la prórroga.
 * 4. Actualiza los plazos activos afectados.
 */
final class RegistrarProrrogaAction
{
    /**
     * Inyecta el servicio que contiene las reglas de negocio de admisión.
     */
    public function __construct(private readonly ProrrogaRulesService $rulesService)
    {
    }

    /**
     * Ejecuta el alta de la prórroga dentro de una transacción.
     *
     * @param DatosEjecucionObras $owner Expediente de ejecución desde el que se lanza la operación.
     * @param array<string, mixed> $data Datos del formulario Filament.
     * @return Prorroga Prórroga persistida.
     * @throws ValidationException Cuando faltan plazos activos o la petición incumple reglas.
     *
     * @see ProrrogaRulesService::validateRequest()
     * @param array<string, mixed> $data
     */
    public function execute(DatosEjecucionObras $owner, array $data): Prorroga
    {
        return DB::transaction(function () use ($owner, $data): Prorroga {
            $nextNumSec = (int) (Prorroga::query()
                ->where('expediente_id', $owner->expediente_id)
                ->max('NumSec') ?? 0) + 1;

            $data['PlanObra'] = (string) ($owner->Codigo_Plan ?? '');
            $data['NumObra'] = (int) ($owner->numero_obra ?? 0);
            $data['SubRef'] = (int) ($owner->subreferencia ?? 0);
            $data['AoObra'] = (int) ($owner->ao_ejecucion ?? 0);
            $data['NumSec'] = $nextNumSec;
            $data['expediente_id'] = (string) $owner->expediente_id;
            $data['team_id'] = $owner->team_id;

            $tipo = ProrrogaTipo::from((string) ($data['tipo_prorroga'] ?? ProrrogaTipo::EJECUCION->value));
            $targetFases = $this->resolveTargetFases($tipo, (string) ($data['alcance_prorroga'] ?? ''));

            $requestDate = Carbon::parse((string) ($data['FecPeticionProrrogaDeDip'] ?? now()->toDateString()));
            $newLimit = Carbon::parse((string) ($data['fecha_limite_nueva'] ?? now()->toDateString()));

            $plazos = PlazoObraActivo::query()
                ->where('expediente_id', $owner->expediente_id)
                ->whereIn('fase', $targetFases)
                ->where('activo', true)
                ->lockForUpdate()
                ->get();

            if ($plazos->isEmpty()) {
                throw ValidationException::withMessages([
                    'tipo_prorroga' => 'No existen plazos activos para este expediente y fase. Cree o active un plazo antes de registrar la prorroga.',
                ]);
            }

            $firstOldLimit = Carbon::parse((string) $plazos->first()->fecha_fin);

            if (empty($data['fecha_limite_anterior'])) {
                $data['fecha_limite_anterior'] = $firstOldLimit->toDateString();
            }

            if (empty($data['dias_concedidos'])) {
                $data['dias_concedidos'] = max(0, $firstOldLimit->diffInDays($newLimit, false));
            }

            /** @var PlazoObraActivo $plazo */
            foreach ($plazos as $plazo) {
                $oldLimit = Carbon::parse((string) $plazo->fecha_fin);
                $requestedDays = max(0, $oldLimit->diffInDays($newLimit, false));

                $validation = $this->rulesService->validateRequest(
                    $plazo,
                    $requestDate,
                    $requestedDays,
                    $tipo === ProrrogaTipo::EJECUCION,
                );

                if (! $validation->valid) {
                    throw ValidationException::withMessages([
                        'fecha_limite_nueva' => $validation->message ?? 'No se puede registrar la prórroga.',
                    ]);
                }
            }

            /** @var Prorroga $prorroga */
            $prorroga = Prorroga::query()->create($data);

            /** @var PlazoObraActivo $plazo */
            foreach ($plazos as $plazo) {
                $oldLimit = Carbon::parse((string) $plazo->fecha_fin);
                $plazo->forceFill(['fecha_fin' => $newLimit->toDateString()]);
                $plazo->dias_prorroga_acumulados += max(0, $oldLimit->diffInDays($newLimit, false));
                $plazo->fuente_ultima_actualizacion = 'prorroga';
                $plazo->save();
            }

            $justificationDeadline = $this->rulesService->calculateJustificationDeadline($newLimit);
            $justificationPlazos = PlazoObraActivo::query()
                ->where('expediente_id', $owner->expediente_id)
                ->where('fase', 'justificacion')
                ->where('activo', true)
                ->get();

            foreach ($justificationPlazos as $plazo) {
                $plazo->forceFill(['fecha_fin' => $justificationDeadline->toDateString()]);
                $plazo->fuente_ultima_actualizacion = 'prorroga';
                $plazo->save();
            }

            return $prorroga;
        });
    }

    /**
     * Determina qué fases del expediente deben verse afectadas por la prórroga.
     *
     * @param ProrrogaTipo $tipo Tipo funcional de la prórroga.
     * @param string $alcance Alcance concreto cuando la prórroga es normativa.
     * @return list<string> Lista de fases sobre las que se actualizarán los plazos.
     */
    private function resolveTargetFases(ProrrogaTipo $tipo, string $alcance): array
    {
        if ($tipo === ProrrogaTipo::EJECUCION) {
            return ['ejecucion'];
        }

        if ($tipo === ProrrogaTipo::JUSTIFICACION) {
            return ['justificacion'];
        }

        return match ($alcance) {
            'proyecto_memoria' => ['proyecto_memoria'],
            'documentacion' => ['documentacion'],
            default => ['proyecto_memoria', 'documentacion'],
        };
    }
}
