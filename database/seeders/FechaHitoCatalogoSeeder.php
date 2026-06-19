<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FechaHitoCatalogo;
use Illuminate\Database\Seeder;

final class FechaHitoCatalogoSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array<string, mixed>> $hitos */
        $hitos = config('date_rules.hitos_catalogo', []);

        foreach ($hitos as $indice => $hito) {
            FechaHitoCatalogo::query()->updateOrCreate(
                ['codigo_hito' => (string) ($hito['codigo_hito'] ?? '')],
                [
                    'descripcion' => (string) ($hito['descripcion'] ?? ''),
                    'tabla_origen' => (string) ($hito['tabla_origen'] ?? ''),
                    'campo_origen' => (string) ($hito['campo_origen'] ?? ''),
                    'fase' => isset($hito['fase']) ? (string) $hito['fase'] : null,
                    'obligatorio' => (bool) ($hito['obligatorio'] ?? false),
                    'repetible' => (bool) ($hito['repetible'] ?? false),
                    'activa' => true,
                    'orden' => $indice + 1,
                ],
            );
        }
    }
}
