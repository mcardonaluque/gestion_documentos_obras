<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class CatalogoTiposDocumentalesSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['TD01', 'Resolución'],
            ['TD02', 'Acuerdo'],
            ['TD03', 'Contrato'],
            ['TD04', 'Convenio'],
            ['TD07', 'Notificación'],
            ['TD05', 'Declaración'],
            ['TD06', 'Comunicación'],
            ['TD08', 'Publicación'],
            ['TD09', 'Acuse de recibo'],
            ['TD10', 'Acta'],
            ['TD11', 'Certificado'],
            ['TD12', 'Diligencia'],
            ['TD13', 'Informe'],
            ['TD14', 'Solicitud'],
            ['TD15', 'Denuncia'],
            ['TD16', 'Alegación'],
            ['TD17', 'Recursos'],
            ['TD18', 'Comunicación ciudadano'],
            ['TD19', 'Factura'],
            ['TD20', 'Otros incautados'],
            ['TD99', 'Otros'],
        ];

        $timestamp = now()->format('Ymd H:i:s');

        foreach ($tipos as [$identificador, $descripcion]) {
            DB::connection('Obras')
                ->table('catalogo_tipos_documentales')
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
