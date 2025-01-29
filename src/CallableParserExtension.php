<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 * @phpstan-type ParseExpression = callable(non-empty-string): Expression
 */
final class CallableParserExtension implements ParserExtension
{
    /** @var ParseExpression */
    private $parse;

    /**
     * @param ParseExpression $parse
     */
    public function __construct(
        callable $parse,
    ) {
        $this->parse = $parse;
    }

    public function parse(string $cron): Expression
    {
        return ($this->parse)($cron);
    }
}
