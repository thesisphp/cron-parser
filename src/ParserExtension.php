<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
interface ParserExtension
{
    /**
     * @throws ParserException
     */
    public function parse(Expression $expression): Time;
}
