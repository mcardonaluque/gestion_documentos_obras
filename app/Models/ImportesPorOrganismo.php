<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Distribución de importes de una obra por organismo financiador.
 *
 * Detalla la aportación económica de cada organismo (diputación,
 * ayuntamiento, otras administraciones) por fase. Tabla
 * 'ImportesPorOrganismo' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'expediente_id'. Se relaciona con la obra, su ejecución,
 * certificaciones, Expediente y Team.
 */
class ImportesPorOrganismo extends Model
{

    use HasFactory;
    protected $connection='Obras';
    protected $table='ImportesPorOrganismo';
    protected $primaryKey='expediente_id';
    public $incrementing=false;
    protected $keyType='string';

    /** Ficha de inicio de obra; devuelve relación vacía si el expediente no es válido. */
    public function obra()
    {
        $expedienteId = $this->expediente_id;

        if (blank($expedienteId) || (string) $expedienteId === '0') {
            return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id')
                ->whereRaw('1 = 0');
        }

        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
    }

    /** Datos de ejecución de la obra asociada a los importes. */
    public function obrasejecucion():BelongsTo{
        return $this->belongsTo (DatosEjecucionObras::class,'expediente_id','expediente_id');
    }
    /** Certificaciones vinculadas a los importes por organismo. */
    public function certificaciones():BelongsToMany{
        return $this->belongsToMany (certificaciones::class,'expediente_id','expediente_id');
    }

    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    /** Expediente administrativo asociado a los importes. */
    public function expedientes(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }
    /** Obtiene los importes por organismo de una fase (pendiente de implementar). */
    public function getImportesPorOrganismoFase(int $fase)
    {

    }
}
