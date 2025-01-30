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
     */
    public function replace(string $cron): string;
}
