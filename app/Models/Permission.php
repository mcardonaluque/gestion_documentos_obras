<?php

namespace App\Models;

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
    //
    public $incrementing = false;
}
