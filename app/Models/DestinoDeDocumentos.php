<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Catálogo de destinos y procedencias de documentos.
 *
 * Define los posibles organismos o unidades de destino/origen de la
 * documentación de los expedientes. Tabla 'DestinosDeDocumentos' en la
 * conexión 'Obras' (SQL Server), sin timestamps.
 * Tiene muchos DocumentoGenerico y DocumentoExpediente.
 */
class DestinoDeDocumentos extends Model
{
    //
    protected $connection='Obras';
    protected $table='DestinosDeDocumentos';
    protected $primaryKey='id';
    protected $fillable=['destino'];
    public $timestamps = false;
    use HasFactory;
    /** Documentos genéricos que usan este destino. */
    public function docuemntosgenericos()
    {
        return $this->hasMany(DocumentoGenerico::class);
    }
    /** Documentos de expediente cuyo destino es este registro. */
    public function documentos()
    {
        return $this->hasMany(DocumentoExpediente::class,'id','destino');
    }
}
