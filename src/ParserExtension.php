<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
interface ParserExtension
{
    /**
     * @param non-empty-string $cron
     */
    public function supports(string $cron): bool;

    /**
     * @param non-empty-string $cron
     * @throws ParserException
     */
    public function parse(string $cron): Expression;
}
