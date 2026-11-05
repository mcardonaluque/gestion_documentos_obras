<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Integrante de la cadena de validación o firma de una subsanación.
 */
class SubsanacionFirmante extends Model
{
    public const TIPO_VALIDACION = 'V';

    public const TIPO_FIRMA = 'F';

    protected $connection = 'Obras';

    protected $table = 'subsanacion_firmantes';

    protected $fillable = [
        'subsanacion_id',
        'user_id',
        'id_rol',
        'tipo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
        ];
    }

    /** Usuario de la aplicación que interviene en la cadena. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Solicitud de subsanación a la que pertenece. */
    public function subsanacion(): BelongsTo
    {
        return $this->belongsTo(Subsanacion::class);
    }
}
