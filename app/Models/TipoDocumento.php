<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoDocumento extends Model
{
    use HasFactory;
    protected $connection='Tablas';
    protected $table='tipo_documentos';
    protected $primaryKey = 'id';
     protected $fillable=['IdTipo','nombre','descripcion'];
     public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
