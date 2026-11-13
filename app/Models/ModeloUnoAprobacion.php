<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeloUnoAprobacion extends Model
{
    protected $connection = 'Obras';

    protected $table = 'modelos_uno_aprobacion';

    protected $fillable = [
        'expediente_id', 'codigo_plan', 'numero_obra', 'subreferencia', 'ao_ejecucion',
        'nombre_obra', 'entidad_local', 'secretario_nombre', 'fecha_documento',
        'tipo_competencia', 'team_id',
    ];

    protected function casts(): array
    {
        return [
            'numero_obra' => 'integer',
            'subreferencia' => 'integer',
            'ao_ejecucion' => 'integer',
            'fecha_documento' => 'date',
            'team_id' => 'integer',
        ];
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
}
