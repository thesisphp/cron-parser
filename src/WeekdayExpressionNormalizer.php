<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
final class WeekdayExpressionNormalizer implements ExpressionNormalizer
{
    /** @var array<non-empty-string, non-empty-string> */
    private const WEEKDAY_TO_SEQUENCE = [
        'MON' => '1',
        'TUE' => '2',
        'WED' => '3',
        'THU' => '4',
        'FRI' => '5',
        'SAT' => '6',
        'SUN' => '0',
    ];

    public function normalize(Expression $expression): Expression
    {
        return $expression->withWeekdays(self::WEEKDAY_TO_SEQUENCE[$expression->weekdays] ?? $expression->weekdays);
    }
}
