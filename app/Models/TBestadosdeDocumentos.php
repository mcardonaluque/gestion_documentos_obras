<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo de estados de los documentos de expediente.
 *
 * Tabla 'TBEstadodeDocumentos' en la conexión 'Obras' (SQL Server), con
 * clave primaria autoincremental 'id'. Tiene muchos DocumentoExpediente.
 */
class TBestadosdeDocumentos extends Model
{
    //
    use HasFactory;
    protected $connection='Obras';
   protected $table='TBEstadodeDocumentos';
   protected $primaryKey='id';

                                                                                                                                                                                                                                                                                           public $incrementing = true;

   /** Documentos de expediente que se encuentran en este estado. */
   public function documentos(){
    return $this->hasMany (DocumentoExpediente::class,'estado','id');
}
}

