<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Registro de auditoría para eventos relevantes del sistema.
 *
 * Almacena el tipo de evento, su carga útil serializada y la información de
 * usuario o tenant necesaria para posteriores consultas y trazabilidad.
 */
class EventLog extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'payload',
        'tenant_id',
        'user_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
    ];
}
