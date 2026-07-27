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

        if (Schema::hasColumn('NormativaPPAC', 'created_at')) {
            DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [created_at] datetime2(7) NULL');
        }

        if (Schema::hasColumn('NormativaPPAC', 'updated_at')) {
            DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [updated_at] datetime2(7) NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('NormativaPPAC')) {
            return;
        }

        if (Schema::hasColumn('NormativaPPAC', 'created_at')) {
            DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [created_at] datetime NULL');
        }

        if (Schema::hasColumn('NormativaPPAC', 'updated_at')) {
            DB::statement('ALTER TABLE [NormativaPPAC] ALTER COLUMN [updated_at] datetime NULL');
        }
    }
};
