<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Tabla pivote entre DocumentoExpediente y User.
 *
 * Modelo mínimo sin configuración adicional que usa la conexión por defecto;
 * sirve de soporte a la vinculación documento-usuario.
 */
class DocumentoExpedienteUser extends Model
{
    use HasFactory;
}
