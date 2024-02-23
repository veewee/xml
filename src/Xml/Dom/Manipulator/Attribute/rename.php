<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Attribute;

use \DOM\Attr;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Fun\tap;
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename as rename_xmlns_attribute;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 */
function rename(\DOM\Attr $target, string $newQName, ?string $newNamespaceURI = null): \DOM\Attr
{
    return disallow_issues(static fn (): \DOM\Attr => match(true) {
        is_xmlns_attribute($target) => rename_xmlns_attribute($target, $newQName),
        default => tap(fn () => $target->rename($newNamespaceURI, $newQName))($target)
    });
}
