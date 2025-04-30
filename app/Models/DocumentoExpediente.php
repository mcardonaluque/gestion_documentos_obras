<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DocumentoExpediente extends Model
{
    use HasFactory;
    protected $connection='Obras';
    //protected $table='documentacionexpediente';
    protected $table='dbo.documentacionexpedientes';
    protected $primaryKey='Expediente';

    protected $fillable =[];

    function expediente(){
        return $this->belongsTo(Expediente::class,'Expediente','Expediente',);
    }
    function obra(){
        return $this->belongsTo(DatosDeInicioDeObras::class);
    }
    function documento(){
        return $this->belongsTo(DocumentoGenerico::class);
    }
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
