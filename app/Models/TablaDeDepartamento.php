<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TablaDeDepartamento extends Model
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='TablaDeDepartamentos';
    protected $primaryKey='CODIGO_DPTO';
    public function proyecto_redactor():HasMany
    {
        return $this->hasMany(Proyecto::class, 'Servicio_redactor', 'CODIGO_DPTO' );
    }
    public function proyecto_director():HasMany
    {
        return $this->hasMany(Proyecto::class, 'servicio_direccion', 'CODIGO_DPTO' );
    }
    public function proyecto_gestor():HasMany
    {
        return $this->hasMany(Proyecto::class, 'Servicio_Gestor', 'CODIGO_DPTO' );
    }
    public function usuarios():HasMany{
        return $this->hasMany(Usuario::class);
    }
    
}
