<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Operación configurada de la API del tramitador electrónico.
 *
 * Define cada endpoint invocable (método HTTP, ruta, tipo de contenido y
 * timeout) de la integración con el tramitador. Tabla
 * 'tramitador_api_operaciones' en la conexión 'Obras' (SQL Server).
 * Tiene muchos TramitadorApiParametro.
 */
class TramitadorApiOperacion extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'tramitador_api_operaciones';

    protected $dateFormat = 'Ymd H:i:s';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'metodo_http',
        'ruta',
        'tipo_contenido',
        'timeout_segundos',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'timeout_segundos' => 'integer',
            'activa' => 'boolean',
        ];
    }

    /** Parámetros de entrada/salida configurados para la operación. */
    public function parametros(): HasMany
    {
        return $this->hasMany(TramitadorApiParametro::class, 'operacion_id');
    }
}
