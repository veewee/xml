<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Attribute;

use \Dom\Attr;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Fun\tap;
use function VeeWee\Xml\Dom\Manipulator\Xmlns\rename as rename_xmlns_attribute;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 */
function rename(\Dom\Attr $target, string $newQName, ?string $newNamespaceURI = null): \Dom\Attr
{
    return disallow_issues(static fn (): \Dom\Attr => match(true) {
        is_xmlns_attribute($target) => rename_xmlns_attribute($target, $newQName),
        default => (function() use ($target, $newNamespaceURI, $newQName): \Dom\Attr {
            $target->rename($newNamespaceURI, $newQName);
            return $target;
        })()
    });
}
