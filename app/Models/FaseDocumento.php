<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Catálogo de fases documentales del ciclo de tramitación.
 *
 * Define las fases (proyecto, aprobación, cesión, contratación, ejecución,
 * justificación...) por las que pasa la documentación. Tabla
 * 'fase_documentos' en la conexión 'Obras' (SQL Server), clave primaria
 * string 'cod_fase' y sin timestamps. Tiene muchos DocumentoGenerico.
 */
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
    /** Tipos de documentos genéricos pertenecientes a esta fase. */
    function documentos_genericos(){
        return $this->hasMany(DocumentoGenerico::class,'fase_doc','cod_fase');
    }
    /** Team (ayuntamiento/ámbito) propietario del registro. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
