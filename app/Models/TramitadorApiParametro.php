<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Parámetro de una operación de la API del tramitador electrónico.
 *
 * Describe cada parámetro de entrada o salida (ubicación, tipo de dato,
 * obligatoriedad, formato y validación) de una operación. Tabla
 * 'tramitador_api_parametros' en la conexión 'Obras' (SQL Server).
 * Pertenece a TramitadorApiOperacion.
 */
class TramitadorApiParametro extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'tramitador_api_parametros';

    protected $dateFormat = 'Ymd H:i:s';

    protected $fillable = [
        'operacion_id',
        'direccion',
        'ubicacion',
        'nombre',
        'etiqueta',
        'tipo_dato',
        'obligatorio',
        'formato',
        'valor_por_defecto',
        'reglas_validacion',
        'ruta_json',
        'orden',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'operacion_id' => 'integer',
            'obligatorio' => 'boolean',
            'orden' => 'integer',
            'activo' => 'boolean',
        ];
    }

    /** Operación de la API a la que pertenece el parámetro. */
    public function operacion(): BelongsTo
    {
        return $this->belongsTo(TramitadorApiOperacion::class, 'operacion_id');
    }
}
