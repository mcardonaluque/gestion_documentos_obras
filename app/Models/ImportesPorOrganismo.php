<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImportesPorOrganismo extends Model
{
    
    use HasFactory;
    protected $connection='Obras';
    protected $table='ImportesPorOrganismo';
    protected $primaryKey='expediente_id';
    public $incrementing=false;
    protected $keyType='string';
    public function obra()
    {
        return $this->belongsTo(DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
    }
    
    public function obrasejecucion():BelongsTo{
        return $this->belongsTo (DatosEjecucionObras::class,'expediente_id','expediente_id');
    }
    public function certificaciones():BelongsToMany{
        return $this->belongsToMany (certificaciones::class,'expediente_id','expediente_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function expedientes(): BelongsTo
    {
        return $this->belongsTo(Expediente::class);
    }
    public function getImportesPorOrganismoFase(int $fase)
    {
     
    }
}
