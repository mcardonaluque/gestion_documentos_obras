<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
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

    public function rule(): BelongsTo
    {
        return $this->belongsTo(DateValidationRule::class, 'date_validation_rule_id');
    }
}
