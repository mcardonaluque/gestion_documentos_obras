<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        if (! Schema::connection('Obras')->hasTable('NormativaPPAC')) {
            return;
        }

        if (! Schema::connection('Obras')->hasColumn('NormativaPPAC', 'Codigo_Plan')) {
            DB::connection('Obras')->statement('ALTER TABLE [NormativaPPAC] ADD [Codigo_Plan] varchar(20) NULL');
        }

        // La normativa pasa a ser única por año y plan en lugar de solo por año.
        DB::connection('Obras')->statement("IF EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'normativappac_ao_plan_unique' AND object_id = OBJECT_ID('NormativaPPAC')) DROP INDEX [normativappac_ao_plan_unique] ON [NormativaPPAC]");
        DB::connection('Obras')->statement("IF NOT EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'normativappac_ao_plan_codigo_plan_unique' AND object_id = OBJECT_ID('NormativaPPAC')) CREATE UNIQUE INDEX [normativappac_ao_plan_codigo_plan_unique] ON [NormativaPPAC] ([ao_plan], [Codigo_Plan])");
    }

    public function down(): void
    {
        if (! Schema::connection('Obras')->hasTable('NormativaPPAC')) {
            return;
        }

        DB::connection('Obras')->statement("IF EXISTS (SELECT 1 FROM sys.indexes WHERE name = 'normativappac_ao_plan_codigo_plan_unique' AND object_id = OBJECT_ID('NormativaPPAC')) DROP INDEX [normativappac_ao_plan_codigo_plan_unique] ON [NormativaPPAC]");
    }
};
