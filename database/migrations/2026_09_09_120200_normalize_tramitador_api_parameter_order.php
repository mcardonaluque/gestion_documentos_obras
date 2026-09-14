<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        $connection = DB::connection($this->connection);
        $grupos = $connection
            ->table('tramitador_api_parametros')
            ->select('operacion_id', 'direccion')
            ->distinct()
            ->get();

        foreach ($grupos as $grupo) {
            $parametros = $connection
                ->table('tramitador_api_parametros')
                ->where('operacion_id', $grupo->operacion_id)
                ->where('direccion', $grupo->direccion);

            if ($grupo->direccion === 'entrada') {
                $parametros->orderByRaw("CASE ubicacion WHEN 'path' THEN 1 WHEN 'query' THEN 2 WHEN 'header' THEN 3 WHEN 'body' THEN 4 ELSE 5 END");
            }

            $ids = $parametros
                ->orderBy('orden')
                ->orderBy('id')
                ->pluck('id');

            foreach ($ids as $indice => $id) {
                $connection
                    ->table('tramitador_api_parametros')
                    ->where('id', $id)
                    ->update(['orden' => $indice + 1]);
            }
        }
    }

    public function down(): void
    {
        // El orden previo no puede reconstruirse tras normalizarlo.
    }
};
