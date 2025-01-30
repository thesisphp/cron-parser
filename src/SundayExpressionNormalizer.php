<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
final class SundayExpressionNormalizer implements ExpressionNormalizer
{
    private const SUNDAY_SEQUENCE_MAPPING = ['7' => '0'];

    public function normalize(Expression $expression): Expression
    {
        return $expression->withWeekdays(self::SUNDAY_SEQUENCE_MAPPING[$expression->weekdays] ?? $expression->weekdays);
    }
}
