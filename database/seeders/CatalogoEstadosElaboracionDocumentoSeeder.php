<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CatalogoEstadosElaboracionDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['EE01', 'Original'],
            ['EE02', 'Copia electrónica auténtica con cambio de formato'],
            ['EE03', 'Copia electrónica auténtica de documento papel'],
            ['EE04', 'Copia electrónica parcial auténtica'],
            ['EE99', 'Otros'],
        ];

        $timestamp = now()->format('Ymd H:i:s');

        foreach ($estados as [$identificador, $descripcion]) {
            DB::connection('Obras')
                ->table('catalogo_estados_elaboracion_documento')
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
