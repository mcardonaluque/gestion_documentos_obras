<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Obra cedida a otra administración (normalmente al ayuntamiento).
 *
 * Registra los datos de la cesión de la obra dentro del ciclo del plan.
 * Tabla 'ObrasCedidas' en la conexión 'Obras' (SQL Server), con clave
 * primaria string 'expediente_id' y sin timestamps. Se relaciona con
 * Expediente, DatosDeInicioDeObras, importes y Team.
 */
class ObraCedida extends Model
{
    //
    use HasFactory;
    protected $connection='Obras';
    protected $table='ObrasCedidas';
    protected $primaryKey='expediente_id';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
   // protected $fillable=['TipoActuacion'];
   protected $fillable = [];
   //protected $guarded =[];

    /** Expediente administrativo asociado a la cesión. */
    public function expediente(){
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id');
    }
    /** Ficha de inicio de la obra cedida. */
    public function Obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class);
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }

    /** Importes globales de la obra cedida. */
    public function importes():HasOne{
        return $this->hasOne(ImportesDeObras::class,'expediente_id','expediente_id');
    }
    /** Distribución de importes por organismo financiador. */
    public function importesdeorganismos():HasMany{
        return $this->hasMany(ImportesPorOrganismo::class,'expediente_id','expediente_id');
    }
}
