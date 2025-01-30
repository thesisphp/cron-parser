<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
final class MonthExpressionNormalizer implements ExpressionNormalizer
{
    /** @var array<non-empty-string, non-empty-string> */
    private const MONTH_TO_SEQUENCE = [
        'JAN' => '1',
        'FEB' => '2',
        'MAR' => '3',
        'APR' => '4',
        'MAY' => '5',
        'JUN' => '6',
        'JUL' => '7',
        'AUG' => '8',
        'SEP' => '9',
        'OCT' => '10',
        'NOV' => '11',
        'DEC' => '12',
    ];

    public function normalize(Expression $expression): Expression
    {
        return $expression->withMonths(self::MONTH_TO_SEQUENCE[strtoupper($expression->months)] ?? $expression->months);
    }
}
