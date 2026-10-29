<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('Obras')->hasColumn('NormativaPPAC', 'fecha_convenio')) {
            DB::connection('Obras')->statement('ALTER TABLE [NormativaPPAC] ADD [fecha_convenio] smalldatetime NULL');
        }
    }

    public function down(): void
    {
        if (Schema::connection('Obras')->hasColumn('NormativaPPAC', 'fecha_convenio')) {
            DB::connection('Obras')->statement('ALTER TABLE [NormativaPPAC] DROP COLUMN [fecha_convenio]');
        }
    }
};
