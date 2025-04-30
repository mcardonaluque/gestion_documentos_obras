<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FaseDocumento extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='fase_documentos';
    protected $primaryKey='cod_fase';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable=['cod_fase','nombre', 'descripcion'];
    function documentos_genericos(){
        return $this->hasMany(DocumentoGenerico::class,'fase_doc','cod_fase');
    }
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
