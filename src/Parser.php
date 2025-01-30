<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
final class Parser
{
    /** @var array<non-empty-string, Time> */
    private static array $times = [];

    /**
     * @param list<ExpressionReplacer> $replacers
     * @param list<ExpressionNormalizer> $normalizers
     */
    public function __construct(
        private readonly ParserExtension $extension,
        private readonly array $replacers = [],
        private readonly array $normalizers = [],
    ) {}

    public static function standard(): self
    {
        return new self(
            extension: new StandardParserExtension(),
            replacers: [new AliasExpressionReplacer()],
            normalizers: [new SundayExpressionNormalizer(), new WeekdayExpressionNormalizer()],
        );
    }

    /**
     * @param non-empty-string $cron
     * @throws ParserException
     */
    public function parse(string $cron): Time
    {
        if (isset(self::$times[$cron])) {
            return self::$times[$cron];
        }

        $time = $this->doParse($cron);

        self::$times[$cron] = $time;
        // After the expression is normalized, it may be different (e.g. @monthly -> 0 0 1 1 * * *), so we cache the normalized value for fast access too.
        self::$times[(string) $time] = $time;

        return $time;
    }

    /**
     * @param non-empty-string $cron
     * @throws ParserException
     */
    private function doParse(string $cron): Time
    {
        $expression = array_reduce(
            $this->normalizers,
            static fn(Expression $expression, ExpressionNormalizer $normalizer): Expression => $normalizer->normalize($expression),
            Expression::parse(array_reduce(
                $this->replacers,
                static fn(string $cron, ExpressionReplacer $replacer): string => $replacer->replace($cron),
                $cron,
            )),
        );

        return $this->extension->parse($expression);
    }
}
