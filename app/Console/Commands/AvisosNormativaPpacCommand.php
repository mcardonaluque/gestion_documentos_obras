<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DateHitos\SincronizadorFechasHitosService;
use App\Services\DateRules\NormativaPpacAvisosService;
use Illuminate\Console\Command;

final class AvisosNormativaPpacCommand extends Command
{
    protected $signature = 'normativa-ppac:avisos {--expediente=} {--simular} {--sin-sincronizar}';

    protected $description = 'Genera avisos y notificaciones por los hitos de fechas de la normativa PPAC';

    public function handle(NormativaPpacAvisosService $avisos, SincronizadorFechasHitosService $sincronizador): int
    {
        $expedienteId = is_string($this->option('expediente')) && $this->option('expediente') !== ''
            ? (string) $this->option('expediente')
            : null;
        $simular = (bool) $this->option('simular');

        // Los hitos cumplidos se leen de la proyección de hitos por expediente.
        if (! $simular && ! $this->option('sin-sincronizar')) {
            $expedienteId !== null
                ? $sincronizador->sincronizarExpediente($expedienteId)
                : $sincronizador->sincronizarTodo();
        }

        $resultado = $avisos->ejecutar($simular, $expedienteId);

        $this->info(($simular ? '[SIMULACION] ' : '') . sprintf(
            'Expedientes: %d. Avisos %s: %d. Notificaciones: %d. Avisos cerrados: %d.',
            $resultado['expedientes'],
            $simular ? 'a generar' : 'generados',
            $resultado['avisos'],
            $resultado['notificaciones'],
            $resultado['cerrados'],
        ));

        return self::SUCCESS;
    }
}
