<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\DateRuleType;
use App\Models\DateValidationRule;

/**
 * DTO inmutable con el resultado de evaluar una regla de fechas sobre un campo.
 *
 * Lo produce DateRuleEvaluator y lo consumen DateRuleExecutionLogger (auditoría)
 * y DateRuleNotifier (notificaciones) para decidir bloqueos y avisos.
 */
final class DateRuleEvaluationResult
{
    public function __construct(
        public readonly DateValidationRule $rule,
        public readonly string $field,
        public readonly bool $passed,
        public readonly bool $triggered,
        public readonly string $messageStage,
        public readonly DateRuleType $type,
        public readonly string $message,
        public readonly ?string $leftValue,
        public readonly ?string $rightValue,
    ) {
    }
}
