<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CatalogoTipoFirmasSeeder extends Seeder
{
    public function run(): void
    {
        $tiposFirma = [
            ['TF01', 'CSV'],
            ['TF02', 'XAdES internally detached signature'],
            ['TF03', 'XAdES enveloped signature'],
            ['TF04', 'CAdES detached/explicit signature'],
            ['TF05', 'CAdES attached/implicit signature'],
            ['TF06', 'PAdES'],
        ];

        $timestamp = now()->format('Ymd H:i:s');

        foreach ($tiposFirma as [$identificador, $descripcion]) {
            DB::connection('Obras')
                ->table('catalogo_tipo_firmas')
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
