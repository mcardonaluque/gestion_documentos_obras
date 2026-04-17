<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Relación explícita entre un expediente y el usuario responsable de tramitarlo.
 *
 * Este modelo soporta la visibilidad segmentada de expedientes y la gestión
 * administrativa de asignaciones por equipo o por perfil funcional.
 *
 * @property int $id
 * @property string $expediente_id
 * @property int $user_id
 * @property int|null $assigned_by
 * @property int|null $team_id
 */
class ExpedienteUserAssignment extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'expediente_user_assignments';

    protected $dateFormat = 'Ymd H:i:s';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'expediente_id',
        'user_id',
        'assigned_by',
        'team_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'assigned_by' => 'integer',
            'team_id' => 'integer',
        ];
    }

    /** Expediente asignado. */
    public function expediente(): BelongsTo
    {
        return $this->belongsTo(Expediente::class, 'expediente_id', 'expediente_id');
    }

    /** Usuario destinatario de la asignación. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Usuario que realizó la asignación. */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /** Team o ámbito organizativo en el que se registró la asignación. */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
