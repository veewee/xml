<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @param callable(): bool $loader
 * @throws RuntimeException
 */
function load(callable $loader): void
{
    disallow_issues(
        static function () use ($loader) {
            disallow_libxml_false_returns(
                $loader(),
                'Could not load the DOM Document'
            );
        }
    );
}
