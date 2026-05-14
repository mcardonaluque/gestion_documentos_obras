<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TbMunicipio;
use App\Models\TbProvincias;

class Contratista extends Model
{
    //
    protected $connection='Obras';
    protected $table='TBContratistas';
    protected $primaryKey='Codigo_contratista';
    protected $attributes = [
        'Municipio' => null,
        'MunicipioFiscal' => null,
        'Provincia' => null,
        'ProvinciaFiscal' => null,
    ];
    protected static array $municipioNombreCache = [];

    public $incrementing=false;
    public $timestamps=false;
    public function pendcontratacionobras(){
        return $this->hasMany (PendienteContratacionObra::class,'Codigo_contratista','Codcontratista');
    }
    public function tipoContratista(){
        return $this->belongsTo (TipoContratista::class,'Tipo_contratista','Tipo_contratista');
    }
    public function municipio(){
        return $this->belongsTo(TbMunicipio::class, 'Municipio', 'Codigo_Municipio');
    }
    public function municipioFiscal(){
        return $this->belongsTo(TbMunicipio::class, 'MunicipioFiscal', 'Codigo_Municipio');
    }
    public function provincia(){
        return $this->belongsTo(TbProvincias::class, 'Provincia', 'PR');
    }
    public function provinciaFiscal(){
        return $this->belongsTo(TbProvincias::class, 'ProvinciaFiscal', 'PR');
    }

    public function getMunicipioNombreAttribute(): ?string
    {
        return $this->resolverNombreMunicipio(
            $this->getAttribute('Provincia'),
            $this->getAttribute('Municipio'),
        );
    }

    public function getMunicipioFiscalNombreAttribute(): ?string
    {
        return $this->resolverNombreMunicipio(
            $this->getAttribute('ProvinciaFiscal'),
            $this->getAttribute('MunicipioFiscal'),
        );
    }

    protected function resolverNombreMunicipio(mixed $provincia, mixed $municipio): ?string
    {
        if (blank($provincia) || blank($municipio)) {
            return null;
        }

        $key = (string) $provincia . '|' . (string) $municipio;

        if (! array_key_exists($key, static::$municipioNombreCache)) {
            $nombre = TbMunicipio::query()
                ->where('Codigo_Provincia', $provincia)
                ->where('Codigo_Municipio', $municipio)
                ->value('Municipio');

            static::$municipioNombreCache[$key] = filled($nombre)
                ? trim((string) $nombre)
                : null;
        }

        return static::$municipioNombreCache[$key];
    }
}
