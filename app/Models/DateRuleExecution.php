<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Registro de ejecución de una regla de validación de fechas.
 *
 * Guarda el resultado de cada evaluación (valor evaluado, comparado,
 * superada o disparada) con huella para evitar duplicados.
 * Tabla 'date_rule_executions' en la conexión 'Obras' (SQL Server).
 * Pertenece a una DateValidationRule.
 *
 * @property int $id
 * @property int $date_validation_rule_id
 * @property string $model_type
 * @property string $model_id
 * @property string|null $expediente_id
 * @property string $source_table
 * @property string $source_field
 * @property string|null $evaluated_value
 * @property string|null $compared_value
 * @property bool $passed
 * @property bool $triggered
 * @property string $severity
 * @property string $action
 * @property string $message
 * @property string $fingerprint
 * @property \Illuminate\Support\Carbon $evaluated_at
 */
class DateRuleExecution extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'date_rule_executions';

    protected $dateFormat = 'Ymd H:i:s';

    protected $fillable = [
        'date_validation_rule_id',
        'model_type',
        'model_id',
        'expediente_id',
        'source_table',
        'source_field',
        'evaluated_value',
        'compared_value',
        'passed',
        'triggered',
        'severity',
        'action',
        'message',
        'fingerprint',
        'evaluated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
            'triggered' => 'boolean',
            'evaluated_at' => 'datetime',
        ];
    }

    /** Regla de validación de fechas que originó esta ejecución. */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(DateValidationRule::class, 'date_validation_rule_id');
    }
}
