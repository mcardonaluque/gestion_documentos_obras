<?php

declare(strict_types=1);

namespace App\Services\DateRules;

use App\DTOs\DateRuleEvaluationResult;
use App\Enums\DateRuleCondition;
use App\Enums\DateRuleOperation;
use App\Models\DateValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Evalua reglas de fechas definidas en base de datos para una entidad Eloquent.
 */
final class DateRuleEvaluator
{
    /**
     * @param array<int, string> $changedFields
     * @return Collection<int, DateRuleEvaluationResult>
     */
    public function evaluate(Model $model, array $changedFields, DateRuleOperation $operation): Collection
    {
        $table = $this->normalizeIdentifier($model->getTable());
        $fields = array_map(fn (string $value): string => $this->normalizeIdentifier($value), $changedFields);

        /** @var Collection<int, DateValidationRule> $rules */
        $rules = DateValidationRule::query()
            ->active()
            ->whereIn('operacion', [DateRuleOperation::BOTH->value, $operation->value])
            ->get();

        $results = collect();

        foreach ($rules as $rule) {
            if ($this->normalizeIdentifier($rule->tabla1) !== $table) {
                continue;
            }

            if (! in_array($this->normalizeIdentifier($rule->campo1), $fields, true)) {
                continue;
            }

            if (! $this->matchesPhaseAndState($model, $rule)) {
                continue;
            }

            $leftRaw = $this->resolveFieldValue($model, $rule->tabla1, $rule->campo1);
            $rightRaw = $this->resolveFieldValue($model, $rule->tabla2, $rule->campo2);

            $leftDate = $this->toDate($leftRaw);
            $rightDate = $this->toDate($rightRaw);

            $passed = $this->evaluateCondition($rule->condicion, $leftDate, $rightDate, $rule->plazo_dias, $rule->aviso_dias);
            $triggered = $rule->dispara_si_cumple ? $passed : ! $passed;

            $results->push(new DateRuleEvaluationResult(
                rule: $rule,
                field: $rule->campo1,
                passed: $passed,
                triggered: $triggered,
                type: $rule->tipo,
                message: $rule->mensaje,
                leftValue: $leftRaw,
                rightValue: $rightRaw,
            ));
        }

        /** @var Collection<int, DateRuleEvaluationResult> $results */
        return $results;
    }

    /**
     * @param Collection<int, DateRuleEvaluationResult> $results
     * @return array<string, array<int, string>>
     */
    public function blockingErrorsByField(Collection $results): array
    {
        $errors = [];

        foreach ($results as $result) {
            if (! $result->triggered || ! $result->type->isBlocking()) {
                continue;
            }

            $errors[$result->field] ??= [];
            $errors[$result->field][] = $result->message;
        }

        return $errors;
    }

    private function matchesPhaseAndState(Model $model, DateValidationRule $rule): bool
    {
        $phase = $this->stringOrNull($model->getAttribute('fase') ?? $model->getAttribute('cod_fase'));
        $state = $this->stringOrNull($model->getAttribute('estado') ?? $model->getAttribute('cod_estado') ?? $model->getAttribute('codigo_estado_obra'));

        if ($rule->fase !== null && $rule->fase !== '' && $phase !== $rule->fase) {
            return false;
        }

        if ($rule->estado !== null && $rule->estado !== '' && $state !== $rule->estado) {
            return false;
        }

        return true;
    }

    private function resolveFieldValue(Model $model, ?string $tableName, ?string $fieldName): ?string
    {
        if ($tableName === null || $fieldName === null || $tableName === '' || $fieldName === '') {
            return null;
        }

        $table = $this->normalizeIdentifier($tableName);
        $field = $this->normalizeIdentifier($fieldName);

        if ($table === $this->normalizeIdentifier($model->getTable())) {
            return $this->stringOrNull($model->getAttribute($fieldName));
        }

        $expedienteId = $this->resolveExpedienteId($model);

        if ($expedienteId === null) {
            return null;
        }

        $connection = method_exists($model, 'getConnectionName') ? ($model->getConnectionName() ?? 'Obras') : 'Obras';

        /** @var object|null $rowObject */
        $rowObject = DB::connection($connection)
            ->table($tableName)
            ->select([$fieldName])
            ->where('expediente_id', $expedienteId)
            ->first();

        if ($rowObject === null) {
            return null;
        }

        /** @var array<string, mixed> $row */
        $row = get_object_vars($rowObject);

        foreach ($row as $key => $value) {
            if ($this->normalizeIdentifier((string) $key) === $field) {
                return $this->stringOrNull($value);
            }
        }

        return null;
    }

    private function resolveExpedienteId(Model $model): ?string
    {
        $candidates = [
            $model->getAttribute('expediente_id'),
            $model->getAttribute('Expediente'),
            $model->getAttribute('n_exp'),
        ];

        foreach ($candidates as $candidate) {
            $value = $this->stringOrNull($candidate);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function evaluateCondition(
        DateRuleCondition $condition,
        ?Carbon $leftDate,
        ?Carbon $rightDate,
        ?int $deadlineDays,
        ?int $warningDays,
    ): bool {
        return match ($condition) {
            DateRuleCondition::IS_DATE => $leftDate !== null,
            DateRuleCondition::AFTER => $leftDate !== null && $rightDate !== null && $leftDate->gt($rightDate),
            DateRuleCondition::AFTER_OR_EQUAL => $leftDate !== null && $rightDate !== null && ($leftDate->gt($rightDate) || $leftDate->equalTo($rightDate)),
            DateRuleCondition::BEFORE => $leftDate !== null && $rightDate !== null && $leftDate->lt($rightDate),
            DateRuleCondition::BEFORE_OR_EQUAL => $leftDate !== null && $rightDate !== null && ($leftDate->lt($rightDate) || $leftDate->equalTo($rightDate)),
            DateRuleCondition::MAX_DAYS_BETWEEN => $leftDate !== null && $rightDate !== null && $deadlineDays !== null
                && $leftDate->diffInDays($rightDate) <= $deadlineDays,
            DateRuleCondition::MIN_DAYS_BETWEEN => $leftDate !== null && $rightDate !== null && $deadlineDays !== null
                && $leftDate->diffInDays($rightDate) >= $deadlineDays,
            DateRuleCondition::DEADLINE_NOT_EXPIRED => $leftDate !== null && $deadlineDays !== null
                && now()->lessThanOrEqualTo($leftDate->copy()->addDays($deadlineDays)->endOfDay()),
            DateRuleCondition::DAYS_TO_DEADLINE_LTE => $leftDate !== null && $deadlineDays !== null && $warningDays !== null
                && now()->diffInDays($leftDate->copy()->addDays($deadlineDays), false) <= $warningDays,
        };
    }

    private function toDate(?string $raw): ?Carbon
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        try {
            return Carbon::parse($raw);
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeIdentifier(string $value): string
    {
        $normalized = trim(strtolower($value));

        if (str_contains($normalized, '.')) {
            $parts = explode('.', $normalized);
            return (string) end($parts);
        }

        return $normalized;
    }

    /**
     * @param mixed $value
     */
    private function stringOrNull(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return null;
    }
}
