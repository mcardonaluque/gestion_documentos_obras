<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('PlazosObraActivos')) {
            return;
        }

        DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [fecha_inicio] datetime2(7) NOT NULL');
        DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [fecha_fin] datetime2(7) NOT NULL');

        if (Schema::hasColumn('PlazosObraActivos', 'created_at')) {
            DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [created_at] datetime2(7) NULL');
        }

        if (Schema::hasColumn('PlazosObraActivos', 'updated_at')) {
            DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [updated_at] datetime2(7) NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('PlazosObraActivos')) {
            return;
        }

        DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [fecha_inicio] date NOT NULL');
        DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [fecha_fin] date NOT NULL');

        if (Schema::hasColumn('PlazosObraActivos', 'created_at')) {
            DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [created_at] datetime NULL');
        }

        if (Schema::hasColumn('PlazosObraActivos', 'updated_at')) {
            DB::statement('ALTER TABLE [PlazosObraActivos] ALTER COLUMN [updated_at] datetime NULL');
        }
    }
};
