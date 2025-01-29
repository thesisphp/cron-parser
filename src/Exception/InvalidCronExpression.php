<?php

declare(strict_types=1);

namespace Thesis\Cron\Exception;

use Thesis\Cron\ParserException;

/**
 * @api
 */
final class InvalidCronExpression extends \UnexpectedValueException implements ParserException {}
