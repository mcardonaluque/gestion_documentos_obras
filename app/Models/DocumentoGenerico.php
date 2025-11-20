<?php

namespace App\Models;

use Filament\Actions\Concerns\BelongsToGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentoGenerico extends Model
{
    use HasFactory;
  
    protected $connection='Obras';
    protected $table='documento_genericos';
    protected $primaryKey='id';
    protected $foreignKey='expediente_id';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable=['nombre','fase_doc','fase_siguiente','cod_tipo_doc','descripcion','generado','con_plantilla','plantilla','rutaplantilla','fasesiguiente','cod_destino','cod_origen','obligatorio', 'cod_estado'];
    protected $casts = [
        'con_plantilla' => 'boolean',
    ];

    public function fasedoc(){
        return $this->belongsTo(FaseDocumento::class,'cod_fase','fase_doc');
    }
    public function fasedocsig(){
        return $this->belongsTo(FaseDocumento::class,'cod_fase','fase_siguiente');
    }
   
   public function destino(): BelongsTo
    {
        return $this->belongsTo(DestinoDeDocumentos::class);
    }
    public function tipodoc(){
          return $this->belongsTo(TipoDocumento::class,'cod_tipo_doc','id');
     }
    public function documtoexpediente():HasMany{
        return $this->hasMany (DocumentoExpediente::class,'cod_documento','id');
    } 
    public function estados():BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class, 'cod_estado', 'cod_estado' );
    }
} 

