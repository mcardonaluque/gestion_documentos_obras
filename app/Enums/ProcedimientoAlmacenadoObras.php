<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Catálogo de procedimientos almacenados permitidos en la conexión Obras.
 *
 * Añada aquí cada nuevo procedimiento antes de que cualquier servicio pueda
 * ejecutarlo. Así se evita interpolar nombres de procedimientos externos.
 */
enum ProcedimientoAlmacenadoObras: string
{
    case DatosListados = 'dbo.PA_R_DatosListados';
}
