<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
interface ExpressionNormalizer
{
    /**
     * @throws ParserException
     */
    public function normalize(Expression $expression): Expression;
}
