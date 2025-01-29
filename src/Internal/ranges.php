<?php

declare(strict_types=1);

namespace Thesis\Cron\Internal;

if (!\defined('Thesis\Cron\Internal\secondsRange')) {
    \define('Thesis\Cron\Internal\secondsRange', keyedRange(0, 59));
}

if (!\defined('Thesis\Cron\Internal\minutesRange')) {
    \define('Thesis\Cron\Internal\minutesRange', keyedRange(0, 59));
}

if (!\defined('Thesis\Cron\Internal\hoursRange')) {
    \define('Thesis\Cron\Internal\hoursRange', keyedRange(0, 23));
}

if (!\defined('Thesis\Cron\Internal\daysRange')) {
    \define('Thesis\Cron\Internal\daysRange', keyedRange(1, 31));
}

if (!\defined('Thesis\Cron\Internal\monthsRange')) {
    \define('Thesis\Cron\Internal\monthsRange', keyedRange(1, 12));
}

if (!\defined('Thesis\Cron\Internal\weekdayRange')) {
    \define('Thesis\Cron\Internal\weekdayRange', keyedRange(0, 7));
}

/**
 * @param non-negative-int $start
 * @param positive-int $end
 * @return non-empty-array<non-negative-int, true>
 */
function keyedRange(int $start, int $end): array
{
    /** @var non-empty-array<non-negative-int, true> */
    return array_combine(
        range($start, $end),
        array_fill(0, $start === 0 ? $end + 1 : $end, true),
    );
}
