<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
final class Parser
{
    /** @var array<non-empty-string, Expression> */
    private static array $expressions = [];

    /**
     * @param non-empty-list<ExpressionNormalizer> $normalizers
     * @param non-empty-list<ParserExtension> $extensions
     */
    public function __construct(
        private readonly array $normalizers,
        private readonly array $extensions,
    ) {}

    public static function standard(): self
    {
        return new self(
            normalizers: [new AliasExpressionNormalizer()],
            extensions: [new CallableParserExtension(new StandardParserExtension())],
        );
    }

    /**
     * @param non-empty-string $cron
     * @throws ParserException
     */
    public function parse(string $cron): Expression
    {
        if (isset(self::$expressions[$cron])) {
            return self::$expressions[$cron];
        }

        $expression = $this->doParse($cron);

        self::$expressions[$cron] = $expression;
        // After the expression is normalized, it may be different (e.g. @monthly -> 0 0 1 1 * * *), so we cache the normalized value for fast access too.
        self::$expressions[(string) $expression] = $expression;

        return $expression;
    }

    /**
     * @param non-empty-string $cron
     * @throws ParserException
     */
    private function doParse(string $cron): Expression
    {
        $cron = array_reduce(
            $this->normalizers,
            static fn(string $cron, ExpressionNormalizer $normalizer): string => $normalizer->normalize($cron),
            $cron,
        );

        foreach ($this->extensions as $extension) {
            if ($extension->supports($cron)) {
                return $extension->parse($cron);
            }
        }

        throw new Exception\InvalidCronExpression(\sprintf('None of the extensions were able to parse the expression "%s".', $cron));
    }
}
