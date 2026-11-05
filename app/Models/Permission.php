<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * Permiso de acceso basado en Spatie Permission en la conexión Obras.
 */
class Permission extends SpatiePermission
{
    protected $connection = 'Obras';
}
