<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Firmante genérico configurado para la firma de documentos.
 *
 * Catálogo de personas firmantes reutilizables en la generación documental.
 * Tabla 'fiemante_generico' en la conexión 'Obras' (SQL Server), con clave
 * primaria string y sin timestamps. Se relaciona con Usuario y Team.
 */
class FirmanteGenerico extends Model
{
    protected $connection='Obras';
    protected $table='fiemante_generico';
    protected $primaryKey='id';
    public $incrementing = true;
    public $timestamps = false;
    protected $keyType = 'string';
    use HasFactory;

    /** Usuario interno asociado al firmante. */
    public function user(){
        return $this->belongsTo(Usuario::class);
    }
    /** Team (ayuntamiento/ámbito) propietario del firmante. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
