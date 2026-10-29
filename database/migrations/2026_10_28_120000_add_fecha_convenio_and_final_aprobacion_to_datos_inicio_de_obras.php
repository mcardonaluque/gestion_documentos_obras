<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const COLUMNAS = ['fecha_final_aprobacion_plan', 'fecha_convenio'];

    public function up(): void
    {
        foreach (self::COLUMNAS as $columna) {
            if (! Schema::connection('Obras')->hasColumn('DatosInicioDeObras', $columna)) {
                DB::connection('Obras')->statement("ALTER TABLE [DatosInicioDeObras] ADD [{$columna}] smalldatetime NULL");
            }
        }
    }

    public function down(): void
    {
        foreach (self::COLUMNAS as $columna) {
            if (Schema::connection('Obras')->hasColumn('DatosInicioDeObras', $columna)) {
                DB::connection('Obras')->statement("ALTER TABLE [DatosInicioDeObras] DROP COLUMN [{$columna}]");
            }
        }
    }
};
