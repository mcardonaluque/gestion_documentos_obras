<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLA = 'NormativaPPAC';

    public function up(): void
    {
        $this->convertir('smalldatetime');
    }

    public function down(): void
    {
        $this->convertir('datetime2(0)');
    }

    private function convertir(string $tipo): void
    {
        $db = DB::connection('Obras');

        if (! Schema::connection('Obras')->hasTable(self::TABLA)) {
            return;
        }

        $columnas = $db->select(
            "SELECT COLUMN_NAME AS nombre, IS_NULLABLE AS nullable
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_NAME = ? AND COLUMN_NAME LIKE 'fecha[_]%'
               AND DATA_TYPE IN ('datetime', 'datetime2', 'smalldatetime')",
            [self::TABLA]
        );

        foreach ($columnas as $columna) {
            $nombre = $columna->nombre;

            // Los defaults dependen de la columna y bloquean el ALTER COLUMN.
            $defaults = $db->select(
                "SELECT dc.name AS nombre, OBJECT_DEFINITION(dc.object_id) AS definicion
                 FROM sys.default_constraints dc
                 JOIN sys.columns c ON c.object_id = dc.parent_object_id AND c.column_id = dc.parent_column_id
                 WHERE dc.parent_object_id = OBJECT_ID(?) AND c.name = ?",
                [self::TABLA, $nombre]
            );

            foreach ($defaults as $default) {
                $db->statement("ALTER TABLE [" . self::TABLA . "] DROP CONSTRAINT [{$default->nombre}]");
            }

            $null = $columna->nullable === 'YES' ? 'NULL' : 'NOT NULL';
            $db->statement("ALTER TABLE [" . self::TABLA . "] ALTER COLUMN [{$nombre}] {$tipo} {$null}");

            foreach ($defaults as $default) {
                $db->statement("ALTER TABLE [" . self::TABLA . "] ADD CONSTRAINT [{$default->nombre}] DEFAULT {$default->definicion} FOR [{$nombre}]");
            }
        }
    }
};
