<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DateRules\NormativaPpacAvisosService;
use Illuminate\Console\Command;

/**
 * Comando Artisan normativa-ppac:avisos.
 *
 * Genera (o simula con --simular) los avisos y notificaciones de los hitos de
 * fechas de la normativa PPAC delegando en NormativaPpacAvisosService. Admite
 * acotar a un único expediente con --expediente. Programado diariamente a las
 * 03:00 en routes/console.php.
 */
final class AvisosNormativaPpacCommand extends Command
{
    protected $signature = 'normativa-ppac:avisos {--expediente=} {--simular}';

    protected $description = 'Genera avisos y notificaciones por las reglas de fechas que comparan con la normativa PPAC';

    /**
     * Ejecuta el servicio de avisos y muestra las estadísticas resultantes.
     */
    public function handle(NormativaPpacAvisosService $avisos): int
    {
        $expedienteId = is_string($this->option('expediente')) && $this->option('expediente') !== ''
            ? (string) $this->option('expediente')
            : null;
        $simular = (bool) $this->option('simular');

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
