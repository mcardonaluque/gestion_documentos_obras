<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Importes económicos globales de una obra.
 *
 * Resume el presupuesto y los importes acumulados del expediente de obra.
 * Tabla 'ImportesDeObras' en la conexión 'Obras' (SQL Server), con clave
 * primaria 'expediente_id'. Aplica un global scope que excluye registros
 * sin expediente. Se relaciona con DatosDeInicioDeObras y Team.
 */
class ImportesDeObras extends Model
{

    use HasFactory;
    protected $connection='Obras';
    protected $table='ImportesDeObras';
    protected $primaryKey='expediente_id';
    protected $casts = [
        'Expediente' => 'string',
    ];

    /** Global scope: excluye los registros con expediente_id nulo. */
    protected static function booted(): void
    {
        static::addGlobalScope('exclude_invalid_expediente', function (Builder $builder): void {
            $builder->whereNotNull('expediente_id');
        });
    }

    /** Ficha de inicio de obra; devuelve relación vacía si el expediente no es válido. */
    public function obra(): BelongsTo
    {
        $expedienteId = $this->expediente_id;

        if (blank($expedienteId) || (string) $expedienteId === '0') {
            return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id')
                ->whereRaw('1 = 0');
        }

        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
