<?php

declare(strict_types=1);

namespace App\Services\DateRules;

use App\DTOs\DateRuleEvaluationResult;
use App\Models\DateRuleExecution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class DateRuleExecutionLogger
{
    /**
     * @param Collection<int, DateRuleEvaluationResult> $results
     */
    public function log(Model $model, Collection $results): void
    {
        $expedienteId = $this->resolveExpedienteId($model);

        foreach ($results as $result) {
            if (! $result->triggered) {
                continue;
            }

            $fingerprint = sha1(implode('|', [
                (string) $result->rule->id,
                $model::class,
                (string) $model->getKey(),
                $result->field,
                $result->messageStage,
                $result->message,
                now()->toDateString(),
            ]));

            DateRuleExecution::query()->firstOrCreate([
                'fingerprint' => $fingerprint,
            ], [
                'date_validation_rule_id' => $result->rule->id,
                'model_type' => $model::class,
                'model_id' => (string) $model->getKey(),
                'expediente_id' => $expedienteId,
                'source_table' => $model->getTable(),
                'source_field' => $result->field,
                'evaluated_value' => $result->leftValue,
                'compared_value' => $result->rightValue,
                'passed' => $result->passed,
                'triggered' => $result->triggered,
                'severity' => $result->type->value,
                'action' => $result->rule->accion->value,
                'message' => $result->message,
                'evaluated_at' => now(),
            ]);
        }
    }

    private function resolveExpedienteId(Model $model): ?string
    {
        $value = $model->getAttribute('expediente_id') ?? $model->getAttribute('Expediente') ?? $model->getAttribute('n_exp');

        return is_scalar($value) ? (string) $value : null;
    }
}
