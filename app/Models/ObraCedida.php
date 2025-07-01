<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObraCedida extends Model
{
    //
    use HasFactory;
    protected $connection='Obras';
    protected $table='ObrasCedidas';
    protected $primaryKey='Expediente';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    public $timestamps = false;
   // protected $fillable=['TipoActuacion'];
   protected $fillable = [];
   //protected $guarded =[];
      
    public function expediente(){
        return $this->belongsTo(Expediente::class);
    }
    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
}
