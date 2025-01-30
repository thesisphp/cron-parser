# Cron Parser

## Installation

```shell
composer require thesis/cron-parser
```

## Basic usage

```php
<?php

declare(strict_types=1);

use Thesis\Cron;

$parser = Cron\Parser::standard();
echo $parser->parse('* * * * *')->match(new \DateTimeImmutable('2025-01-29 00:00:00')); // true
```

## Configure parser

```php
<?php

declare(strict_types=1);

use Thesis\Cron;

$parser = new Cron\Parser(
    extension: new Cron\StandardParserExtension(),
    replacers: [new Cron\AliasExpressionReplacer(['@secondly' => '* * * * * *'])],
    normalizers: [new Cron\SundayExpressionNormalizer(), new Cron\WeekdayExpressionNormalizer()],
);

$time = $parser->parse('@secondly');

echo $time->match(new \DateTimeImmutable('2025-01-29 00:00:00')); // true
echo $time->match(new \DateTimeImmutable('2025-01-29 00:00:01')); // true
echo $time->match(new \DateTimeImmutable('2025-01-29 00:00:02')); // true
```

## Parser architecture

`ExpressionReplacer` works with a cron string and is needed to replace the entire string.
For example, `AliasExpressionReplacer` implements this interface and replaces popular expressions like `@hourly, @monthly` with the corresponding cron expressions.
This is the first step in parsing the cron expression.

The next step is normalization, and it is performed using `ExpressionNormalizer`, which works with an `Expression` object, which is some intermediate representation of the cron string.
At this point, you can replace expressions atomically. For example, `WeekdayExpressionNormalizer` replaces only the weekdays from expressions like `MON, TUE, FRI` with numeric ones.

In the last step, called the parsing step, the expression is finally processed and transformed from an `Expression` object to a `Time` object.
At this stage you can use `StandardParserExtension` or add your own.
