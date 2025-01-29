<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
interface ExpressionNormalizer
{
    /**
     * @param non-empty-string $cron
     * @return non-empty-string
     */
    public function normalize(string $cron): string;
}
