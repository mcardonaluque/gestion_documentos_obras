<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('NormativaPPAC')) {
            return;
        }

        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_publicacion_definitiva] datetime2(7) NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_terminacion_plan] datetime2(7) NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_justificacion_plan] datetime2(7) NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_presentacion_proyecto] datetime2(7) NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_presentacion_documentacion] datetime2(7) NOT NULL');

        if (! Schema::hasColumn('NormativaPPAC', 'fecha_cesion_proyecto')) {
            DB::statement('ALTER TABLE [NormativaPPAC] ADD [fecha_cesion_proyecto] datetime2(7) NULL');
        }

        if (! Schema::hasColumn('NormativaPPAC', 'fecha_cesion_documentacion')) {
            DB::statement('ALTER TABLE [NormativaPPAC] ADD [fecha_cesion_documentacion] datetime2(7) NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('NormativaPPAC')) {
            return;
        }

        if (Schema::hasColumn('NormativaPPAC', 'fecha_cesion_proyecto')) {
            DB::statement('ALTER TABLE [NormativaPPAC] DROP COLUMN [fecha_cesion_proyecto]');
        }

        if (Schema::hasColumn('NormativaPPAC', 'fecha_cesion_documentacion')) {
            DB::statement('ALTER TABLE [NormativaPPAC] DROP COLUMN [fecha_cesion_documentacion]');
        }

        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_publicacion_definitiva] date NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_terminacion_plan] date NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_justificacion_plan] date NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_presentacion_proyecto] date NOT NULL');
        DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [fecha_limite_presentacion_documentacion] date NOT NULL');
    }
};
