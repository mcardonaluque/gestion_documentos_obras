<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tiposjustificante extends Model
{
    //
    protected $connection='Obras';
    protected $table='TiposJustificante';
    protected $primaryKey='id';
    protected $keyType = 'string';
    public $incrementing = false;
    public function certificaciones(){
        return $this->Hasmany(certificaciones::class);
    }
}
