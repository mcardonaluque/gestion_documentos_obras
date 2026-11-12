<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Datos del Anexo III, Modelo 0, para la aprobación de una actuación.
 *
 * Conserva el contexto de la obra como instantánea indexable y las líneas de
 * documentos electrónicos declaradas por la entidad.
 *
 * @property int $id
 * @property string $expediente_id
 * @property string|null $codigo_plan
 * @property int|null $numero_obra
 * @property int|null $subreferencia
 * @property int|null $ao_ejecucion
 * @property string|null $nombre_obra
 * @property int $anualidad
 * @property string $secretario_nombre
 * @property string $entidad_local
 * @property string|null $programa_denominacion
 * @property string|null $proyecto_actuacion
 * @property string|null $referencia_actuacion
 * @property int|null $team_id
 * @property-read Expediente $expediente
 * @property-read Team|null $team
 * @property-read Collection<int, ModeloCeroDocumentoAprobacion> $documentos
 */
class ModeloCeroAprobacion extends Model
{
    protected $connection = 'Obras';

    protected $table = 'modelos_cero_aprobacion';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'expediente_id',
        'codigo_plan',
        'numero_obra',
        'subreferencia',
        'ao_ejecucion',
        'nombre_obra',
        'anualidad',
        'secretario_nombre',
        'entidad_local',
        'programa_denominacion',
        'proyecto_actuacion',
        'referencia_actuacion',
        'team_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'numero_obra' => 'integer',
            'subreferencia' => 'integer',
            'ao_ejecucion' => 'integer',
            'anualidad' => 'integer',
            'team_id' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Expediente, $this>
     */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return HasMany<ModeloCeroDocumentoAprobacion, $this>
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(ModeloCeroDocumentoAprobacion::class)
            ->orderBy('orden');
    }
}
