<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function expediente(){
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id');
    }
    public function Obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class);
    }
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }

    public function importes():HasOne{
        return $this->hasOne(ImportesDeObras::class,'expediente_id','expediente_id');
    }
    public function importesdeorganismos():HasMany{
        return $this->hasMany(ImportesPorOrganismo::class,'expediente_id','expediente_id');
    }
}
