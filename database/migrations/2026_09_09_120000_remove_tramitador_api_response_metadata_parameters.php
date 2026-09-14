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

        $parametros
            ->where('direccion', 'salida')
            ->whereIn('nombre', ['status', 'message'])
            ->delete();

        $grupos = $parametros
            ->select('operacion_id', 'direccion')
            ->distinct()
            ->get();

        foreach ($grupos as $grupo) {
            $ids = $parametros
                ->where('operacion_id', $grupo->operacion_id)
                ->where('direccion', $grupo->direccion)
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
        // Los parámetros eliminados pertenecen al contrato fijo de respuesta y no se restauran.
    }
};
