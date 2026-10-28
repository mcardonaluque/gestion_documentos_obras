<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('Obras');

        if (! $schema->hasColumn('Avisos', 'codigo_hito')) {
            DB::connection('Obras')->statement('ALTER TABLE [Avisos] ADD [codigo_hito] varchar(60) NULL');
        }

        if (! $schema->hasColumn('Avisos', 'fecha_hito')) {
            DB::connection('Obras')->statement('ALTER TABLE [Avisos] ADD [fecha_hito] date NULL');
        }

        $tipos = [
            10 => 'Preaviso de fecha límite de normativa PPAC',
            11 => 'Fecha límite de normativa PPAC vencida',
        ];

        foreach ($tipos as $codigo => $descripcion) {
            if (! DB::connection('Obras')->table('TiposAvisos')->where('TipoAviso', $codigo)->exists()) {
                DB::connection('Obras')->table('TiposAvisos')->insert(['TipoAviso' => $codigo, 'Des' => $descripcion]);
            }
        }
    }

    public function down(): void
    {
        $schema = Schema::connection('Obras');

        if ($schema->hasColumn('Avisos', 'fecha_hito')) {
            DB::connection('Obras')->statement('ALTER TABLE [Avisos] DROP COLUMN [fecha_hito]');
        }

        if ($schema->hasColumn('Avisos', 'codigo_hito')) {
            DB::connection('Obras')->statement('ALTER TABLE [Avisos] DROP COLUMN [codigo_hito]');
        }

        DB::connection('Obras')->table('TiposAvisos')->whereIn('TipoAviso', [10, 11])->delete();
    }
};
