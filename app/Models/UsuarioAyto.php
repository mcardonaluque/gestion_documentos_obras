<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Usuario de ayuntamiento vinculado a un municipio.
 *
 * Representa a los usuarios del portal de ayuntamientos; puede actuar como
 * firmante mediante relación polimórfica con User. Usa la conexión por
 * defecto y se relaciona con TablaDeMunicipio.
 */
class UsuarioAyto extends Model
{
    use HasFactory;
    /** Relación polimórfica con el usuario de aplicación como firmante. */
    public function usuarios_firmantes(){
        return $this->morphOne(User::class,'userable');
    }
    /** Municipio al que pertenece el usuario de ayuntamiento. */
    public function Municipio(){
        return $this->belongsTo(TablaDeMunicipio::class);
    }    
}
