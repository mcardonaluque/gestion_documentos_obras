<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DocumentoGenerico extends Model
{
    use HasFactory;
  
    protected $connection='Tablas';
    protected $table='documento_genericos';
    protected $primaryKey='id';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable=['nombre','fase_doc','fase_siguiente','cod_tipo_doc','descripcion','generado','plantilla','rutaplantilla','fasesiguiente','destinatario','obligatorio'];
    protected $casts = [
        'con_plantilla' => 'boolean',
    ];

    public function fasedoc(){
        return $this->belongsTo(FaseDocumento::class,'cod_fase','fase_doc');
    }
    public function fasedocsig(){
        return $this->belongsTo(FaseDocumento::class,'cod_fase','fase_siguiente');
    }
    //function tipodoc(){
    //    return $this->belongsTo(TipoDocumento::class,'idTipo','cod_tipo_doc');
   // }
   public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

