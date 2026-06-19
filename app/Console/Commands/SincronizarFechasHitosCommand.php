<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DateHitos\SincronizadorFechasHitosService;
use Illuminate\Console\Command;

final class SincronizarFechasHitosCommand extends Command
{
    protected $signature = 'date-hitos:sincronizar {--expediente=} {--limpiar}';

    protected $description = 'Sincroniza la proyeccion unificada de hitos de fechas por expediente';

    public function handle(SincronizadorFechasHitosService $sincronizador): int
    {
        $catalogoAfectado = $sincronizador->sincronizarCatalogoDesdeConfig();

        $expedienteId = $this->option('expediente');
        $limpiar = (bool) $this->option('limpiar');

        if (is_string($expedienteId) && $expedienteId !== '') {
            $total = $sincronizador->sincronizarExpediente($expedienteId, $limpiar);
            $this->info('Catalogo sincronizado. Registros creados/actualizados: ' . $catalogoAfectado);
            $this->info('Sincronizacion completada para expediente ' . $expedienteId . '. Registros actualizados: ' . $total);

            return self::SUCCESS;
        }

        $total = $sincronizador->sincronizarTodo($limpiar);
        $this->info('Catalogo sincronizado. Registros creados/actualizados: ' . $catalogoAfectado);
        $this->info('Sincronizacion global completada. Registros actualizados: ' . $total);

        return self::SUCCESS;
    }
}
