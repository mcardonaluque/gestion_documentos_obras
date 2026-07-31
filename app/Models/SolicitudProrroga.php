<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudProrroga extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'SolicitudesProrrogas';

    protected $fillable = [
        'expediente_id',
        'team_id',
        'tipo_prorroga',
        'alcance_prorroga',
        'origen_prorroga',
        'fecha_limite_anterior',
        'fecha_limite_nueva',
        'dias_concedidos',
        'motivo',
        'observaciones_tramitacion',
        'estado',
    ];

    protected $casts = [
        'fecha_limite_anterior' => 'date',
        'fecha_limite_nueva' => 'date',
        'dias_concedidos' => 'integer',
        'team_id' => 'integer',
    ];

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
