<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasOne;


    /**
     * Modelo de expediente administrativo.
     *
     * Actúa como eje de agregación del dominio, enlazando la información de
     * inicio, ejecución, cesión, documentos, importes y asignaciones de usuario.
     */
    class Expediente extends Model
    {
        use HasFactory;
        protected $connection='Obras';
        protected $table='Expedientes';

        protected $primarykey='expediente_id';
        public $incrementing = false;

        protected $keyType = 'string';

        public function getCodigoPlanAttribute(): ?string
        {
            return $this->attributes['codigo_plan']
                ?? $this->attributes['Codigo_Plan']
                ?? null;
        }

        /** Ficha de inicio de obra asociada al expediente. */
        public function obraInicio(){
            return $this->hasOne (DatosDeInicioDeObras::class, 'expediente_id', 'expediente_id');
        }
        public function obraEjecucion(){
            return $this->hasOne(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');

        }
        public function proyecto(): HasOne
        {
            return $this->hasOne(Proyecto::class, 'expediente_id', 'expediente_id');
        }
        public Function obraJustificacion(){
            return $this->hasOne(Justificacion_Obra::class, 'expediente_id', 'expediente_id');

        }
        public function obraCesion(){
            return $this->hasOne(ObraCedida::class, 'expediente_id', 'expediente_id');

        }
        /** Documentos incorporados al expediente durante su ciclo de vida. */
        public function documentos(){
            return $this->HasMany(DocumentoExpediente::class, 'expediente_id', 'expediente_id');

        }
        /** Team o ayuntamiento propietario del expediente. */
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

        public function importesPorOrganismo(): HasMany
        {
            return $this->importesOrganismo();
        }
        public function planes(): BelongsTo{
            return $this->belongsTo(Planes::class, 'codigo_plan', 'codigo_plan');
    }

        /**
         * @return BelongsToMany<User, self>
         */
        /**
         * Usuarios tramitadores asignados expresamente al expediente.
         */
        public function assignedUsers(): BelongsToMany
        {
            return $this->belongsToMany(User::class, 'expediente_user_assignments', 'expediente_id', 'user_id', 'expediente_id', 'id')
                ->withPivot(['assigned_by', 'team_id'])
                ->withTimestamps();
        }

        public function solicitudesProrroga(): HasMany
        {
            return $this->hasMany(Prorroga::class, 'expediente_id', 'expediente_id');
        }
    }
