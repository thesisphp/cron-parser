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
