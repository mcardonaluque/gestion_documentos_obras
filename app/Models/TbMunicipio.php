<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TbProvincias;
use App\Models\Contratista;

class TbMunicipio extends Model
{
    protected $connection = 'Obras';
    protected $table = 'TbMunicipios';
    protected $primaryKey = 'Codigo_Municipio';
    public $incrementing = false;
    public $timestamps = false;

    public function provincia()
    {
        return $this->belongsTo(TbProvincias::class, 'Codigo_Provincia', 'PR');
    }

    public function contratistas()
    {
        return $this->hasMany(Contratista::class, 'Municipio', 'Codigo_Municipio');
    }
}
