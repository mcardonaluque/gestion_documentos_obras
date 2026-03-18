<?php

declare(strict_types=1);

namespace App\Enums;

enum DateRuleCondition: string
{
    case IS_DATE = 'is_date';
    case AFTER = 'after';
    case AFTER_OR_EQUAL = 'after_or_equal';
    case BEFORE = 'before';
    case BEFORE_OR_EQUAL = 'before_or_equal';
    case MAX_DAYS_BETWEEN = 'max_days_between';
    case MIN_DAYS_BETWEEN = 'min_days_between';
    case DEADLINE_NOT_EXPIRED = 'deadline_not_expired';
    case DAYS_TO_DEADLINE_LTE = 'days_to_deadline_lte';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::IS_DATE->value => 'Es fecha valida',
            self::AFTER->value => 'Posterior a',
            self::AFTER_OR_EQUAL->value => 'Posterior o igual a',
            self::BEFORE->value => 'Anterior a',
            self::BEFORE_OR_EQUAL->value => 'Anterior o igual a',
            self::MAX_DAYS_BETWEEN->value => 'Maximo de dias entre fechas',
            self::MIN_DAYS_BETWEEN->value => 'Minimo de dias entre fechas',
            self::DEADLINE_NOT_EXPIRED->value => 'Plazo no vencido',
            self::DAYS_TO_DEADLINE_LTE->value => 'Quedan X dias o menos para el plazo',
        ];
    }

    public function requiresSecondDate(): bool
    {
        return match ($this) {
            self::IS_DATE => false,
            self::DEADLINE_NOT_EXPIRED, self::DAYS_TO_DEADLINE_LTE => false,
            default => true,
        };
    }

    public function requiresDaysValue(): bool
    {
        return match ($this) {
            self::MAX_DAYS_BETWEEN,
            self::MIN_DAYS_BETWEEN,
            self::DEADLINE_NOT_EXPIRED,
            self::DAYS_TO_DEADLINE_LTE => true,
            default => false,
        };
    }
}
