<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DateRules\NormativaPpacAvisosService;
use Illuminate\Console\Command;

final class AvisosNormativaPpacCommand extends Command
{
    protected $signature = 'normativa-ppac:avisos {--expediente=} {--simular}';

    protected $description = 'Genera avisos y notificaciones por las reglas de fechas que comparan con la normativa PPAC';

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
