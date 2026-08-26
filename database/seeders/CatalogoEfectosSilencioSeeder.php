<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CatalogoEfectosSilencioSeeder extends Seeder
{
    public function run(): void
    {
        $efectos = [
            ['ES', 'Estimatorio'],
            ['DES', 'Desestimatorio'],
        ];

        $timestamp = now()->format('Ymd H:i:s');

        foreach ($efectos as [$identificador, $descripcion]) {
            DB::connection('Obras')
                ->table('catalogo_efectos_silencio')
                ->updateOrInsert(
                    ['identificador' => $identificador],
                    [
                        'descripcion' => $descripcion,
                        'updated_at' => $timestamp,
                        'created_at' => $timestamp,
                    ],
                );
        }
    }
}
