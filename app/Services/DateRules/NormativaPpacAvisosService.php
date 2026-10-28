<?php

declare(strict_types=1);

namespace App\Services\DateRules;

use App\Models\Aviso;
use App\Models\Expediente;
use App\Models\ExpedienteFechaHito;
use App\Models\TiposAviso;
use App\Services\DateHitos\NormativaPpacHitosService;
use App\Services\NotificationService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Genera avisos por los hitos de fechas de la normativa PPAC.
 *
 * Cada aviso se registra en la tabla Avisos y se notifica (campanita) a los
 * tramitadores asignados al expediente y a los usuarios del ayuntamiento.
 */
final class NormativaPpacAvisosService
{
    public function __construct(private readonly NormativaPpacHitosService $hitos)
    {
    }

    /**
     * @return array{expedientes: int, avisos: int, notificaciones: int, cerrados: int}
     */
    public function ejecutar(bool $simular = false, ?string $expedienteId = null, ?Carbon $hoy = null): array
    {
        $hoy = ($hoy ?? now())->copy()->startOfDay();
        $stats = ['expedientes' => 0, 'avisos' => 0, 'notificaciones' => 0, 'cerrados' => 0];

        if (! $simular) {
            $this->asegurarTiposAviso();
        }

        Expediente::query()
            ->with(['proyecto', 'assignedUsers:id', 'team.users:id'])
            ->where('ao_ejecucion', '>=', $hoy->year - max(1, (int) config('date_rules.normativa_avisos.anios_revision', 3)) + 1)
            ->when($expedienteId !== null, fn ($query) => $query->where('expediente_id', $expedienteId))
            ->orderBy('id')
            ->chunk(200, function (Collection $expedientes) use ($simular, $hoy, &$stats): void {
                $cumplidos = $this->hitosCumplidos($expedientes);

                foreach ($expedientes as $expediente) {
                    $stats['expedientes']++;
                    $this->procesar($expediente, $cumplidos[$expediente->expediente_id] ?? [], $hoy, $simular, $stats);
                }
            });

        return $stats;
    }

    /**
     * @param  list<string>  $cumplidos
     * @param  array{expedientes: int, avisos: int, notificaciones: int, cerrados: int}  $stats
     */
    private function procesar(Expediente $expediente, array $cumplidos, Carbon $hoy, bool $simular, array &$stats): void
    {
        $avisoDias = (int) config('date_rules.normativa_avisos.aviso_dias', 30);
        $maxVencido = (int) config('date_rules.normativa_avisos.max_dias_vencido', 30);

        foreach ($this->hitos->hitosDe($expediente) as $hito) {
            if (array_intersect($hito['cumplido_con'], $cumplidos) !== []) {
                $stats['cerrados'] += $this->cerrarAvisos($expediente, $hito['codigo_hito'], $simular);

                continue;
            }

            $etapa = NormativaPpacHitosService::etapa($hito['fecha'], $hoy, $avisoDias, $maxVencido);

            if ($etapa === null) {
                continue;
            }

            $tipo = (int) config($etapa === 'vencido'
                ? 'date_rules.normativa_avisos.tipo_vencido'
                : 'date_rules.normativa_avisos.tipo_preaviso');

            $existe = Aviso::query()
                ->where('expediente_id', $expediente->expediente_id)
                ->where('TipoAviso', $tipo)
                ->where('codigo_hito', $hito['codigo_hito'])
                ->whereDate('fecha_hito', $hito['fecha']->toDateString())
                ->exists();

            if ($existe) {
                continue;
            }

            $stats['avisos']++;

            if ($simular) {
                continue;
            }

            $stats['notificaciones'] += $this->registrarYNotificar($expediente, $hito, $etapa, $tipo, $hoy);
        }
    }

    /**
     * @param  array{codigo_hito: string, descripcion: string, fecha: Carbon}  $hito
     */
    private function registrarYNotificar(Expediente $expediente, array $hito, string $etapa, int $tipo, Carbon $hoy): int
    {
        $enviadas = 0;

        DB::connection('Obras')->transaction(function () use ($expediente, $hito, $etapa, $tipo, $hoy, &$enviadas): void {
            Aviso::query()->create([
                'Referencia' => "{$expediente->codigo_plan}.-{$expediente->referencia}/{$expediente->ao_ejecucion}",
                'TipoAviso' => $tipo,
                'Codigo_Plan' => $expediente->codigo_plan,
                'numero_obra' => $expediente->referencia,
                'subreferencia' => $expediente->subreferencia,
                'ao_ejecucion' => $expediente->ao_ejecucion,
                'Usuario' => 'SISTEMA',
                'expediente_id' => $expediente->expediente_id,
                'team_id' => $expediente->team_id,
                'codigo_hito' => $hito['codigo_hito'],
                'fecha_hito' => $hito['fecha']->toDateString(),
                'borrado' => false,
            ]);

            $userIds = $this->destinatarios($expediente);

            if ($userIds === []) {
                return;
            }

            $dias = (int) $hoy->diffInDays($hito['fecha'], false);
            $cuando = $etapa === 'vencido'
                ? 'venció el ' . $hito['fecha']->format('d/m/Y')
                : ($dias === 0 ? 'vence hoy' : "vence el {$hito['fecha']->format('d/m/Y')} (faltan {$dias} días)");

            $notificaciones = NotificationService::sendByTargets(
                title: $etapa === 'vencido' ? 'Hito de normativa PPAC vencido' : 'Preaviso de hito de normativa PPAC',
                message: "Obra {$expediente->nombre_obra} ({$expediente->expediente_id}): {$hito['descripcion']} {$cuando}.",
                type: $etapa === 'vencido' ? 'danger' : 'warning',
                userIds: $userIds,
                data: [
                    'origin' => 'normativa_ppac',
                    'expediente_id' => $expediente->expediente_id,
                    'codigo_hito' => $hito['codigo_hito'],
                    'fecha_hito' => $hito['fecha']->toDateString(),
                ],
            );

            $enviadas = $notificaciones->count();
        });

        return $enviadas;
    }

    /**
     * Tramitadores asignados al expediente y usuarios del ayuntamiento (equipo).
     *
     * @return list<int>
     */
    private function destinatarios(Expediente $expediente): array
    {
        return $expediente->assignedUsers->pluck('id')
            ->merge($expediente->team?->users->pluck('id') ?? [])
            ->map(fn (mixed $id): int => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Los avisos abiertos de un hito ya cumplido se marcan como solucionados.
     */
    private function cerrarAvisos(Expediente $expediente, string $codigoHito, bool $simular): int
    {
        $abiertos = Aviso::query()
            ->where('expediente_id', $expediente->expediente_id)
            ->where('codigo_hito', $codigoHito)
            ->whereNull('FecSolucion');

        if ($simular) {
            return $abiertos->count();
        }

        return $abiertos->update(['FecSolucion' => now()->format('Ymd H:i:s')]);
    }

    /**
     * @param  Collection<int, Expediente>  $expedientes
     * @return array<string, list<string>>
     */
    private function hitosCumplidos(Collection $expedientes): array
    {
        $codigos = collect((array) config('date_rules.normativa_hitos', []))
            ->pluck('cumplido_con')
            ->flatten()
            ->unique()
            ->values()
            ->all();

        return ExpedienteFechaHito::query()
            ->whereIn('expediente_id', $expedientes->pluck('expediente_id')->all())
            ->whereIn('codigo_hito', $codigos)
            ->get(['expediente_id', 'codigo_hito'])
            ->groupBy('expediente_id')
            ->map(fn (Collection $filas): array => $filas->pluck('codigo_hito')->unique()->values()->all())
            ->all();
    }

    private function asegurarTiposAviso(): void
    {
        $tipos = [
            (int) config('date_rules.normativa_avisos.tipo_preaviso') => 'Preaviso de fecha límite de normativa PPAC',
            (int) config('date_rules.normativa_avisos.tipo_vencido') => 'Fecha límite de normativa PPAC vencida',
        ];

        foreach ($tipos as $codigo => $descripcion) {
            if (! TiposAviso::query()->where('TipoAviso', $codigo)->exists()) {
                TiposAviso::query()->create(['TipoAviso' => $codigo, 'Des' => $descripcion]);
            }
        }
    }
}
