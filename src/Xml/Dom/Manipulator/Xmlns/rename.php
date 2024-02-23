<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Xmlns;

use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Xmlns\Xmlns;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 */
function rename(\DOM\Attr $target, string $newQName): \DOM\Attr
{
    disallow_issues(static fn () => $target->rename(Xmlns::xmlns()->value(), $newQName));

    return $target;
}
