<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TablaDeMunicpio extends Model
{
    use HasFactory;
    protected $connection='Tablas';
    protected $table='TablaDeMunicipios';
    protected $primaryKey='codigo_municipio';   

    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'municipio');
    }
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
