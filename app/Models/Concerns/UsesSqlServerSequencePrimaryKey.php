<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Asigna claves a tablas SQL Server heredadas que no usan columnas IDENTITY.
 */
trait UsesSqlServerSequencePrimaryKey
{
    protected static function bootUsesSqlServerSequencePrimaryKey(): void
    {
        static::creating(function (Model $model): void {
            if ($model->getAttribute($model->getKeyName()) !== null) {
                return;
            }

            $connection = DB::connection($model->getConnectionName());

            if ($connection->getDriverName() !== 'sqlsrv') {
                return;
            }

            $table = $model->getTable();
            $sequence = match ($table) {
                'permissions' => 'permissions_id_sequence',
                'roles' => 'roles_id_sequence',
                default => null,
            };

            if ($sequence === null) {
                return;
            }

            $id = $connection->scalar("SELECT NEXT VALUE FOR [dbo].[{$sequence}]");

            if (! is_numeric($id)) {
                throw new \RuntimeException("No se pudo obtener un identificador para {$table}.");
            }

            $model->setAttribute($model->getKeyName(), (int) $id);
        });
    }
}
