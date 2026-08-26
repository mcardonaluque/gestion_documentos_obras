<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CatalogoDuracionProcedimientoSeeder extends Seeder
{
    public function run(): void
    {
        $duraciones = [
            ['SP', 'Sin Plazo Específico', '3'],
            ['NR', 'Normativa Reguladora', '1-6'],
            ['NL', 'Normativa Legal', null],
        ];

        $timestamp = now()->format('Ymd H:i:s');

        foreach ($duraciones as [$identificador, $descripcion, $mesesPorDefecto]) {
            DB::connection('Obras')
                ->table('catalogo_duracion_procedimiento')
                ->updateOrInsert(
                    ['identificador' => $identificador],
                    [
                        'descripcion' => $descripcion,
                        'meses_por_defecto' => $mesesPorDefecto,
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ],
                );
        }
    }
}
