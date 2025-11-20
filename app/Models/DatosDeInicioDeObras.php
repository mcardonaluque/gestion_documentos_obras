<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TablaDeMunicipio;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function expediente():BelongsTo {
        return $this->belongsTo(Expediente::class,'expediente_id','expediente_id',);
    }
    public function municipios(){
        return $this->belongsTo(TablaDeMunicipio::class,'municipio','codigo_municipio')
        ->withDefault([
            'nombre_municipio' => 'Sin municipio', // Valor por defecto
        ]);
    }
    public function ayudaTecnica():HasOne
    {
        return $this->HasOne(AyudaTecnica::class, 'expediente_id', 'expediente_id' );
    }
    public function carrteras():BelongsTo
    {
        return $this->belongsTo(TablaDeCarretera::class, 'carretera', 'Cod_Car' );
    }
    public function importes():HasOne    {
        return $this->hasOne(ImportesDeObras::class, 'expediente_id','expediente_id');
    }
    public function importesPorOrganismo():HasMany
    {
        return $this->hasMany(ImportesPorOrganismo::class, 'expediente_id','expediente_id' );
    }
    public function planeseguridadysalud():HasOne    {
        return $this->hasOne(PlanSeguridadYSalud::class, 'expediente_id','expediente_id');
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
        return $this->belongsTo(Tipoactuacion::class, 'TipoActuacion', 'cod_estado' );
    }
    public function getUbicacionAttribute()
    {
        return  $this->municipio ? $this->municipios->nombre_municipio : $this->carretera;
    }
    public function getObraAttribute()
    {
        //return $this->municipios ? $this->municipios->nombre_municipio : $this->carretera;
        return $this->Codigo_Plan . '-' . $this->numero_obra . '-' . $this->subreferecnia . '-' . $this->ao_ejecucion;
    }
    public function getPlanAttribute()
    {
        return $this->planes ? $this->planes->denominacion_plan : null;
    }
    public function getAportacionAttribute()
    {
        return $this->CompApAyto ? $this->CompApAyto : null;
    }
}
