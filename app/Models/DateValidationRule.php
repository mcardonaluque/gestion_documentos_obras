<?php


declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\DateRuleAction;
use App\Enums\DateRuleCondition;
use App\Enums\DateRuleOperation;
use App\Enums\DateRuleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property string $tabla1
 * @property string|null $tabla2
 * @property string $campo1
 * @property string|null $campo2
 * @property DateRuleCondition $condicion
 * @property string $mensaje
 * @property DateRuleType $tipo
 * @property string|null $fase
 * @property string|null $estado
 * @property DateRuleAction $accion
 * @property bool $activa
 * @property DateRuleOperation $operacion
 * @property int|null $plazo_dias
 * @property int|null $aviso_dias
 * @property string $connection_name
 * @property bool $dispara_si_cumple
 */
class DateValidationRule extends Model
{
    use HasFactory;

    protected $connection = 'Obras';

    protected $table = 'date_validation_rules';

    protected $primaryKey = 'id';

    protected $dateFormat = 'Ymd H:i:s';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tabla1',
        'tabla2',
        'campo1',
        'campo2',
        'condicion',
        'mensaje',
        'tipo',
        'fase',
        'estado',
        'accion',
        'activa',
        'operacion',
        'plazo_dias',
        'aviso_dias',
        'connection_name',
        'dispara_si_cumple',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'condicion' => DateRuleCondition::class,
            'tipo' => DateRuleType::class,
            'accion' => DateRuleAction::class,
            'operacion' => DateRuleOperation::class,
            'activa' => 'boolean',
            'dispara_si_cumple' => 'boolean',
            'plazo_dias' => 'integer',
            'aviso_dias' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activa', true);
    }

    public function faseRelacionada(): BelongsTo
    {
        return $this->belongsTo(FaseDocumento::class, 'fase', 'cod_fase');
    }

    public function estadoRelacionado(): BelongsTo
    {
        return $this->belongsTo(TablaDeEstados::class, 'estado', 'cod_estado');
    }

    public function executions(): HasMany
    {
        return $this->hasMany(DateRuleExecution::class, 'date_validation_rule_id');
    }
}
