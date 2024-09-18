<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Xmlns;

use Dom\Attr;
use VeeWee\Xml\Exception\RuntimeException;
use VeeWee\Xml\Xmlns\Xmlns;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 */
function rename(Attr $target, string $newQName): Attr
{
    disallow_issues(static fn () => $target->rename(Xmlns::xmlns()->value(), $newQName));

    return $target;
}
