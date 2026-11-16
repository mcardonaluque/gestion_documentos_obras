<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeloDosAprobacion extends Model
{
    protected $connection = 'Obras';

    protected $table = 'modelos_dos_aprobacion';

    protected $fillable = [
        'expediente_id',
        'codigo_plan',
        'numero_obra',
        'subreferencia',
        'ao_ejecucion',
        'nombre_obra',
        'codigo_municipio',
        'entidad_local',
        'secretario_nombre',
        'fecha_documento',
        'normativa_cumplida',
        'terrenos_disponibles',
        'autorizaciones',
        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'numero_obra' => 'integer',
            'subreferencia' => 'integer',
            'ao_ejecucion' => 'integer',
            'codigo_municipio' => 'integer',
            'fecha_documento' => 'datetime',
            'normativa_cumplida' => 'boolean',
            'terrenos_disponibles' => 'boolean',
            'team_id' => 'integer',
        ];
    }

    public function getDateFormat(): string
    {
        return 'Ymd H:i:s.v';
    }

    /** @return BelongsTo<Expediente, $this> */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }

    /** @return BelongsTo<Team, $this> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function documentoGenerado(): ?DocumentoExpediente
    {
        return DocumentoExpediente::query()
            ->where('expediente_id', $this->expediente_id)
            ->where('cod_documento', 10)
            ->where('nombre_documento', 'Anexo III. Modelo 2')
            ->first();
    }
}
