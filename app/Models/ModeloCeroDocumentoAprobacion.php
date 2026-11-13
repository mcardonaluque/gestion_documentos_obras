<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Línea de un documento electrónico incluido en un Modelo 0 de aprobación.
 *
 * @property int $id
 * @property int $modelo_cero_aprobacion_id
 * @property int $orden
 * @property string|null $referencia
 * @property string|null $contenido
 * @property string $denominacion
 * @property string $csv
 * @property-read ModeloCeroAprobacion $modeloCeroAprobacion
 */
class ModeloCeroDocumentoAprobacion extends Model
{
    protected $connection = 'Obras';

    protected $table = 'modelo_cero_documentos_aprobacion';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'orden',
        'referencia',
        'contenido',
        'denominacion',
        'csv',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'orden' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<ModeloCeroAprobacion, $this>
     */
    public function modeloCeroAprobacion(): BelongsTo
    {
        return $this->belongsTo(ModeloCeroAprobacion::class);
    }
}
