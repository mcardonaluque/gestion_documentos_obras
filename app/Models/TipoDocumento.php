<?php

namespace App\Models;

use Filament\Support\Contracts\HasColor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='tipo_documentos';
    protected $primaryKey = 'id';
    protected $fillable=['IdTipo','nombre','descripcion','IdTpo'];
    public function documentos(){
        return $this->Hasmany(DocumentoExpediente::class);
    }
    public function documentosgenericos(): HasMany
    {
        return $this->HasMany(DocumentoGenerico::class, 'cod_tipo_doc', 'id');
    }
}
