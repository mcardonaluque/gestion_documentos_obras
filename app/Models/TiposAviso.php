<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TiposAviso extends Model
{
    //
    protected $connection='Obras';
    protected $table='TiposAvisos';
    protected $primaryKey='TipoAviso';
    protected $keyType = 'int';
    public $incrementing = false;
    protected $fillable = ['TipoAviso','Des'];

    public function avisos(): HasMany{
        return $this->hasMany(Aviso::class, 'TipoAviso', 'TipoAviso');
    }
    
}
