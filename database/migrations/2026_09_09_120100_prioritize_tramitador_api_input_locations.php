<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        $parametros = DB::connection($this->connection)
            ->table('tramitador_api_parametros');

        $operaciones = (clone $parametros)
            ->where('direccion', 'entrada')
            ->distinct()
            ->pluck('operacion_id');

        foreach ($operaciones as $operacionId) {
            $ids = (clone $parametros)
                ->where('operacion_id', $operacionId)
                ->where('direccion', 'entrada')
                ->orderByRaw("CASE ubicacion WHEN 'path' THEN 1 WHEN 'query' THEN 2 WHEN 'header' THEN 3 WHEN 'body' THEN 4 ELSE 5 END")
                ->orderBy('orden')
                ->orderBy('id')
                ->pluck('id');

            foreach ($ids as $orden => $id) {
                $parametros->where('id', $id)->update(['orden' => $orden + 1]);
            }
        }
    }

    public function down(): void
    {
        // El orden anterior no puede reconstruirse tras priorizar las ubicaciones.
    }
};
