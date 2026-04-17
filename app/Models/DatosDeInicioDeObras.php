<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TablaDeMunicipio;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo principal de inicio de obras.
 *
 * Representa la ficha maestra de una obra dentro del ciclo administrativo y
 * concentra la mayor parte de relaciones operativas usadas por Filament,
 * impresión documental e importes por organismo.
 */
class DatosDeInicioDeObras extends Model
{
    protected $connection='Obras';
    protected $table='DatosInicioDeObras';
    protected $primaryKey='expediente_id';
    //protected $foreignKey = 'municipio';
    public $incrementing=false;
    protected $keyType='string';
    //public $timestamps = false;

   // protected $fillable=['TipoActuacion'];
   protected $fillable = [
    'EstadoServicioTecnico', // Asegúrate de que esté incluido
    'disponibilidad_terreno',
    'comentario',
    'CompApAyto',
    'TipoObra',
    'PartidaPresupuesto',
    'LicenciaObra',
    'EstadoServicioAdministrativo',
    'TipoPrograma',
    'ayuda',
];
   //protected $guarded =[];
    protected $casts = [
        'municipio' => 'integer', // Convierte municipio a integer
    ];
    use HasFactory;
    /**
     * Indica que la clave de ruta pública del modelo es el expediente.
     */
    public function getRouteKeyName()
    {
        return 'expediente_id';
    }
    /**
     * Devuelve la clave usada por Filament para resolver registros del recurso.
     */
    public static function getRecordRouteKey(): ?string {
         return 'expediente_id';
         }
    /** Relación con el expediente administrativo asociado a la obra. */
    public function expediente():BelongsTo {
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
    /** Municipio principal de la obra, con valor por defecto si no existe. */
    public function municipios(){
        return $this->belongsTo(TablaDeMunicipio::class,'municipio','codigo_municipio')
        ->withDefault([
            'nombre_municipio' => 'Sin municipio', // Valor por defecto
        ]);
    }
    /** Datos de ayuda técnica asociados a la obra. */
    public function ayudaTecnica():HasOne
    {
        return $this->HasOne(AyudaTecnica::class, 'expediente_id', 'expediente_id' );
    }
    public function carrteras():BelongsTo
    {
        return $this->belongsTo(TablaDeCarretera::class, 'carretera', 'Cod_Car' );
    }
    /** Importes globales agregados de la obra. */
    public function importes():HasOne    {
        return $this->hasOne(ImportesDeObras::class, 'expediente_id','expediente_id');
    }
    /** Distribución de importes por organismo financiador o interviniente. */
    public function importesPorOrganismo():HasMany
    {
        return $this->hasMany(ImportesPorOrganismo::class, 'expediente_id','expediente_id' );
    }
    public function planeseguridadysalud():HasOne    {
        return $this->hasOne(Planseguridadysalud::class, 'expediente_id','expediente_id');
    }
    public function proyectos():HasOne    {
        return $this->hasOne(Proyecto::class, 'expediente_id','expediente_id');
    }
    public function datosjecucion():HasOne    {
        return $this->hasOne(DatosEjecucionObras::class, 'expediente_id','expediente_id');
    }
     public function estados():BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class, 'codigo_estado_obra', 'cod_estado' );
    }
    public function planes():BelongsTo
    {
        return $this->belongsTo(Planes::class, 'Codigo_Plan', 'codigo_plan' );
    }
    public function ejecucion():BelongsTo
    {
        return $this->belongsTo(FormaEjecucion::class, 'forma_ejecucion', 'COD_CONTRATA' );
    }
    /** Documentación incorporada al expediente de la obra. */
    public function documentos():HasMany
    {
        return $this->hasMany(DocumentoExpediente::class, 'expediente_id', 'expediente_id' );
    }
    public function certificaciones():HasMany
    {
        return $this->hasMany(certificaciones::class, 'Expediente', 'Expediente' );
    }
    public function tipoactuacion():BelongsTo
    {
        return $this->belongsTo(TipoActuacion::class, 'TipoActuacion', 'codigo' );
    }

    public function team():BelongsTo{
        return $this->belongsTo(Team::class);
    }
    public function actuacion():BelongsTo
    {
        return $this->belongsTo(TipoActuacion::class, 'TipoActuacion', 'cod_estado' );
    }
    /** Devuelve una ubicación legible combinando municipio o carretera. */
    public function getUbicacionAttribute()
    {
        return  $this->municipio ? $this->municipios->nombre_municipio : $this->carretera;
    }
    /** Genera el identificador legible de obra usado en tablas y documentos. */
    public function getObraAttribute()
    {
        //return $this->municipios ? $this->municipios->nombre_municipio : $this->carretera;
        return $this->Codigo_Plan . '-' . $this->numero_obra . '-' . $this->subreferecnia . '-' . $this->ao_ejecucion;
    }
    /** Atajo para obtener la denominación del plan asociado. */
    public function getPlanAttribute()
    {
        return $this->planes ? $this->planes->denominacion_plan : null;
    }
    public function getAportacionAttribute()
    {
        return $this->CompApAyto ? $this->CompApAyto : null;
    }
}
