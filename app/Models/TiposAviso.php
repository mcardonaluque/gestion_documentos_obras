<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TiposAviso extends Model
{
    //
    protected $connection='Obras';
    protected $table='TiposAvisos';
    protected $primaryKey='TipoAviso';

    public function aviso(){
        return $this->Hasmany(Aviso::class);
    }
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
