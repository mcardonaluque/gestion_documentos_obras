<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TablaDeEstados extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='TablaDeEstados';
    protected $primaryKey='cod_estado';
    protected $keyType = 'string';
    public $incrementing = false;
    public function obras(){
        return $this->hasMany (DatosDeInicioDeObras::class,'codigo_estado_obra','cod_estado');
    }
    public function expedientes(){
        return $this->hasMany (Expediente::class,'cod_estado','cod_estado');
    }
    public function documentosgenerico(){
        return $this->hasMany (DocumentoGenerico::class,'cod_estado','cod_estado');
    }
    
}
