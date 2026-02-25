<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;

    class Expediente extends Model
    {
        use HasFactory;
        protected $connection='Obras';
        protected $table='Expedientes';

        protected $primarykey='expediente_id';
        public $incrementing = false; 
        
        protected $keyType = 'string';
    
        public function obraInicio(){
            return $this->hasOne (DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
        }
        public function obraEjecucion(){
            return $this->hasOne(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');
        
        }
        public Function obraJustificacion(){
            return $this->hasOne(Justificacion_Obra::class, 'expediente_id', 'expediente_id');
        
        }
        public function obraCesion(){
            return $this->hasOne(ObraCedida::class, 'expediente_id', 'expediente_id');
        
        }
        public function documentos(){
            return $this->HasMany(DocumentoExpediente::class, 'expediente_id', 'expediente_id');
            
        }
        public function team(): BelongsTo
        {
            return $this->belongsTo(Team::class);
        }
        public function estados(): BelongsTo
        {
            return $this->belongsTo(TablaDeEstados::class, 'cod_estado', 'cod_estado');
        }
        public function ejecucion():BelongsTo
        {
            return $this->belongsTo(FormaEjecucion::class, 'forma_ejecucion', 'COD_CONTRATA' );
        }
        public function municipios():BelongsTo
        {
            return $this->belongsTo(TablaDeMunicipio::class, 'municipio', 'codigo_municipio' );
        }
        public function importes(){
            return $this->hasOne(ImportesDeObras::class, 'expediente_id', 'expediente_id');
            
        }
        public function importesOrganismo(){
            return $this->HasMany(ImportesPorOrganismo::class, 'expediente_id', 'expediente_id');
            
        }
        
    }
