<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            DocumentosExpedientesSeeder::class,
            \Database\Seeders\DateValidationRuleSeeder::class,
                \Database\Seeders\FechaHitoCatalogoSeeder::class,
            CatalogoFuncionesComunesSeeder::class,
            CatalogoFuncionesEspecificasSeeder::class,
            CatalogoDuracionProcedimientoSeeder::class,
            CatalogoEfectosSilencioSeeder::class,
            CatalogoTiposDocumentalesSeeder::class,
            CatalogoEstadosElaboracionDocumentoSeeder::class,
        ]);

    }
}
