<?php

namespace App\Models;

use App\Models\Concerns\UsesSqlServerSequencePrimaryKey;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Permiso de acceso basado en Spatie Permission.
 *
 * Extiende el modelo de permisos de Spatie desactivando el autoincremento
 * de la clave primaria. Usa la tabla y conexión configuradas por el
 * paquete spatie/laravel-permission.
 */
class Permission extends SpatiePermission
{
    use UsesSqlServerSequencePrimaryKey;

    protected $connection = 'Obras';

    public $incrementing = false;
}
