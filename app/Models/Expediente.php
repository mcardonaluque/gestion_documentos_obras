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

        /** Accessor: código de plan tolerante a distintas variantes de nombre de columna. */
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
        /** Datos de la fase de ejecución de la obra del expediente. */
        public function obraEjecucion(){
            return $this->hasOne(DatosEjecucionObras::class, 'expediente_id', 'expediente_id');

        }
        /** Proyecto técnico asociado al expediente. */
        public function proyecto(): HasOne
        {
            return $this->hasOne(Proyecto::class, 'expediente_id', 'expediente_id');
        }
        /** Datos de justificación de la obra del expediente. */
        public Function obraJustificacion(){
            return $this->hasOne(Justificacion_Obra::class, 'expediente_id', 'expediente_id');

        }
        /** Datos de cesión de la obra del expediente. */
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
        /** Estado de tramitación actual del expediente. */
        public function estados(): BelongsTo
        {
            return $this->belongsTo(TablaDeEstados::class, 'cod_estado', 'cod_estado');
        }
        /** Forma de ejecución/contratación de la obra del expediente. */
        public function ejecucion():BelongsTo
        {
            return $this->belongsTo(FormaEjecucion::class, 'forma_ejecucion', 'COD_CONTRATA' );
        }
        /** Municipio en el que se desarrolla la obra del expediente. */
        public function municipios():BelongsTo
        {
            return $this->belongsTo(TablaDeMunicipio::class, 'municipio', 'codigo_municipio' );
        }
        /** Importes globales de la obra del expediente. */
        public function importes(){
            return $this->hasOne(ImportesDeObras::class, 'expediente_id', 'expediente_id');

        }
        /** Distribución de importes por organismo financiador. */
        public function importesOrganismo(){
            return $this->HasMany(ImportesPorOrganismo::class, 'expediente_id', 'expediente_id');

        }

        /** Alias de importesOrganismo(). */
        public function importesPorOrganismo(): HasMany
        {
            return $this->importesOrganismo();
        }
        /** Plan provincial al que pertenece el expediente. */
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

        /** Prórrogas solicitadas sobre el expediente. */
        public function solicitudesProrroga(): HasMany
        {
            return $this->hasMany(Prorroga::class, 'expediente_id', 'expediente_id');
        }
    }
