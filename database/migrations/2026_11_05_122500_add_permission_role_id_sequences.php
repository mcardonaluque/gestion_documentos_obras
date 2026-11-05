<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        $connection = DB::connection('Obras');

        if ($connection->getDriverName() !== 'sqlsrv') {
            return;
        }

        $this->createSequenceWhenRequired($connection, 'permissions');
        $this->createSequenceWhenRequired($connection, 'roles');
    }

    public function down(): void
    {
        $connection = DB::connection('Obras');

        if ($connection->getDriverName() !== 'sqlsrv') {
            return;
        }

        foreach (['permissions_id_sequence', 'roles_id_sequence'] as $sequence) {
            $connection->unprepared(
                "IF OBJECT_ID(N'[dbo].[{$sequence}]', N'SO') IS NOT NULL DROP SEQUENCE [dbo].[{$sequence}]"
            );
        }
    }

    private function createSequenceWhenRequired($connection, string $table): void
    {
        $isIdentity = (int) $connection->scalar(
            "SELECT COLUMNPROPERTY(OBJECT_ID(N'[dbo].[{$table}]'), 'id', 'IsIdentity')"
        );

        if ($isIdentity === 1) {
            return;
        }

        $sequence = "{$table}_id_sequence";
        $nextValue = (int) $connection->scalar("SELECT COALESCE(MAX([id]), 0) + 1 FROM [dbo].[{$table}]");

        $connection->unprepared(
            "IF OBJECT_ID(N'[dbo].[{$sequence}]', N'SO') IS NULL
                EXEC(N'CREATE SEQUENCE [dbo].[{$sequence}] AS BIGINT START WITH {$nextValue} INCREMENT BY 1')"
        );
    }
};
