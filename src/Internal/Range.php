<?php

declare(strict_types=1);

namespace Thesis\Cron\Internal;

/**
 * @internal
 */
final class Range
{
    /** @var non-empty-list<non-negative-int> */
    public readonly array $vector;

    /** @var non-empty-array<non-negative-int, true> */
    public readonly array $hashmap;

    /**
     * @param non-empty-list<non-negative-int> $range
     */
    public function __construct(array $range)
    {
        sort($range, SORT_ASC);

        $this->vector = $range;
        $this->hashmap = array_combine($range, array_fill(0, \count($range), true));
    }

    /**
     * @param non-negative-int $value
     */
    public function in(int $value): bool
    {
        return isset($this->hashmap[$value]);
    }
}
