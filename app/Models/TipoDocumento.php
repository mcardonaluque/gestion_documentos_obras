<?php

namespace App\Models;

use Filament\Support\Contracts\HasColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tipo general de documento (clasificación del catálogo documental).
 *
 * Tabla 'tipo_documentos' en la conexión 'Obras' (SQL Server).
 * Tiene muchos DocumentoExpediente y DocumentoGenerico.
 */
class TipoDocumento extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='tipo_documentos';
    protected $primaryKey = 'id';
    protected $fillable=['IdTipo','nombre','descripcion','IdTpo'];
    /** Documentos de expediente clasificados con este tipo. */
    public function documentos(){
        return $this->Hasmany(DocumentoExpediente::class);
    }
    /** Tipos de documentos genéricos de esta categoría. */
    public function documentosgenericos(): HasMany
    {
        return $this->HasMany(DocumentoGenerico::class, 'cod_tipo_doc', 'id');
    }
}
