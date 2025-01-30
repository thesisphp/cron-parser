<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
interface ParserExtension
{
    public function supports(Expression $expression): bool;

    /**
     * @throws ParserException
     */
    public function parse(Expression $expression): Time;
}
