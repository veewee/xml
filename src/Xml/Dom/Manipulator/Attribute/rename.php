<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Attribute;

use Dom\Attr;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename as rename_xmlns_attribute;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 */
function rename(Attr $target, string $newQName, ?string $newNamespaceURI = null): Attr
{
    return disallow_issues(static fn (): Attr => match(true) {
        is_xmlns_attribute($target) => rename_xmlns_attribute($target, $newQName),
        default => (static function () use ($target, $newNamespaceURI, $newQName): Attr {
            $target->rename($newNamespaceURI, $newQName);
            return $target;
        })()
    });
}
