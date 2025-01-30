<?php

declare(strict_types=1);

namespace Thesis\Cron;

/**
 * @api
 */
final class AliasExpressionReplacer implements ExpressionReplacer
{
    /** @var array<non-empty-string, non-empty-string> */
    private const KNOWN = [
        '@yearly' => '0 0 1 1 *',
        '@annually' => '0 0 1 1 *',
        '@monthly' => '0 0 1 * *',
        '@weekly' => '0 0 * * 0',
        '@daily' => '0 0 * * *',
        '@midnight' => '0 0 * * *',
        '@hourly' => '0 * * * *',
    ];

    /** @var array<non-empty-string, non-empty-string> */
    private readonly array $aliases;

    /**
     * @param array<non-empty-string, non-empty-string> $aliases
     */
    public function __construct(
        array $aliases = [],
    ) {
        $this->aliases = self::KNOWN + $aliases;
    }

    public function replace(string $cron): string
    {
        return $this->aliases[$cron] ?? $cron;
    }
}
