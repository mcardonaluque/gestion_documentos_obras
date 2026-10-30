<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TbMunicipio;
use App\Models\TbProvincias;

/**
 * Contratista adjudicatario de obras (empresa o profesional).
 *
 * Catálogo maestro de contratistas con sus datos de domicilio social y
 * fiscal. Tabla 'TBContratistas' en la conexión 'Obras' (SQL Server),
 * clave primaria 'Codigo_contratista' no autoincremental y sin timestamps.
 * Se relaciona con TipoContratista, TbMunicipio, TbProvincias y
 * PendienteContratacionObra.
 */
class Contratista extends Model
{
    //
    protected $connection='Obras';
    protected $table='TBContratistas';
    protected $primaryKey='Codigo_contratista';
    protected $guarded = [];
    protected $attributes = [
        'Municipio' => null,
        'MunicipioFiscal' => null,
        'Provincia' => null,
        'ProvinciaFiscal' => null,
    ];
    protected static array $municipioNombreCache = [];

    public $incrementing=false;
    public $timestamps=false;
    /** Obras pendientes de contratación adjudicadas a este contratista. */
    public function pendcontratacionobras(){
        return $this->hasMany (PendienteContratacionObra::class,'Codigo_contratista','Codcontratista');
    }
    /** Tipo o categoría del contratista. */
    public function tipoContratista(){
        return $this->belongsTo (TipoContratista::class,'Tipo_contratista','Tipo_contratista');
    }
    /** Municipio del domicilio social del contratista. */
    public function municipio(){
        return $this->belongsTo(TbMunicipio::class, 'Municipio', 'Codigo_Municipio');
    }
    /** Municipio del domicilio fiscal del contratista. */
    public function municipioFiscal(){
        return $this->belongsTo(TbMunicipio::class, 'MunicipioFiscal', 'Codigo_Municipio');
    }
    /** Provincia del domicilio social del contratista. */
    public function provincia(){
        return $this->belongsTo(TbProvincias::class, 'Provincia', 'PR');
    }
    /** Provincia del domicilio fiscal del contratista. */
    public function provinciaFiscal(){
        return $this->belongsTo(TbProvincias::class, 'ProvinciaFiscal', 'PR');
    }

    /** Accessor: nombre del municipio del domicilio social, resuelto con caché. */
    public function getMunicipioNombreAttribute(): ?string
    {
        return $this->resolverNombreMunicipio(
            $this->getAttribute('Provincia'),
            $this->getAttribute('Municipio'),
        );
    }

    /** Accessor: nombre del municipio del domicilio fiscal, resuelto con caché. */
    public function getMunicipioFiscalNombreAttribute(): ?string
    {
        return $this->resolverNombreMunicipio(
            $this->getAttribute('ProvinciaFiscal'),
            $this->getAttribute('MunicipioFiscal'),
        );
    }

    /** Resuelve y cachea el nombre de un municipio a partir de provincia y código. */
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
