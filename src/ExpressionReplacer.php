<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
interface ExpressionReplacer
{
    /**
     * @param non-empty-string $cron
     * @return non-empty-string
     * @throws ParserException
     */
    public function replace(string $cron): string;
}
