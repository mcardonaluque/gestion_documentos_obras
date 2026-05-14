<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TbMunicipio;
class TbProvincias extends Model
{
    protected $connection = 'Obras';
    protected $table = 'TbProvincias';
    protected $primaryKey = 'PR';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    public function municipios()
    {
        return $this->hasMany(TbMunicipio::class, 'Codigo_Provincia', 'PR');
    }
}
