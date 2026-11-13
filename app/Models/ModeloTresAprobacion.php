<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModeloTresAprobacion extends Model
{
    protected $connection = 'Obras';

    protected $table = 'modelos_tres_aprobacion';

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
        'categoria_secretario',
        'anualidad',
        'importe_aprobado_ayuntamiento',
        'partida_presupuestaria',
        'modalidad_presupuesto',
        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'numero_obra' => 'integer',
            'subreferencia' => 'integer',
            'ao_ejecucion' => 'integer',
            'codigo_municipio' => 'integer',
            'anualidad' => 'integer',
            'importe_aprobado_ayuntamiento' => 'decimal:2',
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
