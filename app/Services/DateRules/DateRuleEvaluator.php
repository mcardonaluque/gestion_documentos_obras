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
    private const WILDCARD = '*';

    private const SYSTEM_TODAY_TOKENS = [
        'today',
        '@today',
        'current_date',
    ];

    private const SYSTEM_NOW_TOKENS = [
        'now',
        '@now',
        'current_timestamp',
    ];

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
            if (! $this->ruleMatchesTable($rule->tabla1, $table)) {
                continue;
            }

            $targetFields = $this->resolveTargetFields($rule->campo1, $changedFields, $fields);

            if ($targetFields === []) {
                continue;
            }

            if (! $this->matchesPhaseAndState($model, $rule)) {
                continue;
            }

            foreach ($targetFields as $targetField) {
                $leftRaw = $this->resolveFieldValue($model, $rule->tabla1, $targetField);
                $rightRaw = $this->resolveRightValue($model, $rule->tabla2, $rule->campo2);

                $leftDate = $this->toDate($leftRaw);
                $rightDate = $this->toDate($rightRaw);

                $passed = $this->evaluateCondition($rule->condicion, $leftDate, $rightDate, $rule->plazo_dias, $rule->aviso_dias);
                $triggered = $rule->dispara_si_cumple ? $passed : ! $passed;
                $messageStage = $this->resolveMessageStage($rule->condicion, $passed);
                $message = $this->resolveRuleMessage($rule, $messageStage);

                $results->push(new DateRuleEvaluationResult(
                    rule: $rule,
                    field: $targetField,
                    passed: $passed,
                    triggered: $triggered,
                    messageStage: $messageStage,
                    type: $rule->tipo,
                    message: $message,
                    leftValue: $leftRaw,
                    rightValue: $rightRaw,
                ));
            }
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
        $today = now()->startOfDay();

        return match ($condition) {
            DateRuleCondition::IS_DATE => $leftDate !== null,
            DateRuleCondition::AFTER => $leftDate !== null && $rightDate !== null && $leftDate->gt($rightDate),
            DateRuleCondition::AFTER_OR_EQUAL => $leftDate !== null && $rightDate !== null && ($leftDate->gt($rightDate) || $leftDate->equalTo($rightDate)),
            DateRuleCondition::BEFORE => $leftDate !== null && $rightDate !== null && $leftDate->lt($rightDate),
            DateRuleCondition::BEFORE_OR_EQUAL => $leftDate !== null && $rightDate !== null && ($leftDate->lt($rightDate) || $leftDate->equalTo($rightDate)),
            DateRuleCondition::AFTER_TODAY => $leftDate !== null && $leftDate->copy()->startOfDay()->gt($today),
            DateRuleCondition::AFTER_OR_EQUAL_TODAY => $leftDate !== null && ($leftDate->copy()->startOfDay()->gt($today) || $leftDate->copy()->startOfDay()->equalTo($today)),
            DateRuleCondition::BEFORE_TODAY => $leftDate !== null && $leftDate->copy()->startOfDay()->lt($today),
            DateRuleCondition::BEFORE_OR_EQUAL_TODAY => $leftDate !== null && ($leftDate->copy()->startOfDay()->lt($today) || $leftDate->copy()->startOfDay()->equalTo($today)),
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

    private function resolveMessageStage(DateRuleCondition $condition, bool $passed): string
    {
        if ($condition === DateRuleCondition::DAYS_TO_DEADLINE_LTE && $passed) {
            return 'preaviso';
        }

        return $passed ? 'cumplida' : 'incumplida';
    }

    private function resolveRuleMessage(DateValidationRule $rule, string $stage): string
    {
        if ($stage === 'preaviso' && filled($rule->mensaje_preventivo)) {
            return (string) $rule->mensaje_preventivo;
        }

        if ($stage === 'cumplida' && filled($rule->mensaje_cumplida)) {
            return (string) $rule->mensaje_cumplida;
        }

        if ($stage === 'incumplida' && filled($rule->mensaje_incumplida)) {
            return (string) $rule->mensaje_incumplida;
        }

        return (string) $rule->mensaje;
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

    /**
     * @param array<int, string> $changedFields
     * @param array<int, string> $normalizedChangedFields
     * @return array<int, string>
     */
    private function resolveTargetFields(?string $ruleField, array $changedFields, array $normalizedChangedFields): array
    {
        if ($ruleField === null || $ruleField === '') {
            return [];
        }

        if ($this->isWildcard($ruleField)) {
            return $changedFields;
        }

        $normalizedRuleField = $this->normalizeIdentifier($ruleField);

        foreach ($normalizedChangedFields as $index => $changedField) {
            if ($changedField === $normalizedRuleField) {
                return [$changedFields[$index]];
            }
        }

        return [];
    }

    private function resolveRightValue(Model $model, ?string $tableName, ?string $fieldName): ?string
    {
        $systemDate = $this->resolveSystemDateValue($tableName, $fieldName);

        if ($systemDate !== null) {
            return $systemDate;
        }

        return $this->resolveFieldValue($model, $tableName, $fieldName);
    }

    private function resolveSystemDateValue(?string $tableName, ?string $fieldName): ?string
    {
        $normalizedTable = $tableName !== null ? $this->normalizeIdentifier($tableName) : '';
        $normalizedField = $fieldName !== null ? $this->normalizeIdentifier($fieldName) : '';

        $tokens = array_filter([$normalizedTable, $normalizedField], static fn (string $value): bool => $value !== '');

        foreach ($tokens as $token) {
            if (in_array($token, self::SYSTEM_TODAY_TOKENS, true)) {
                return now()->startOfDay()->toDateTimeString();
            }

            if (in_array($token, self::SYSTEM_NOW_TOKENS, true)) {
                return now()->toDateTimeString();
            }
        }

        return null;
    }

    private function ruleMatchesTable(?string $ruleTable, string $currentTable): bool
    {
        if ($ruleTable === null || $ruleTable === '') {
            return false;
        }

        return $this->isWildcard($ruleTable) || $this->normalizeIdentifier($ruleTable) === $currentTable;
    }

    private function isWildcard(string $value): bool
    {
        return trim($value) === self::WILDCARD;
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
