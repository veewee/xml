<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Element;

use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @throws RuntimeException
 */
function rename(\DOM\Element $target, string $newQName, ?string $newNamespaceURI = null): \DOM\Element
{
    $parts = explode(':', $newQName, 2);
    $newPrefix = $parts[0] ?? '';

    /*
     * To make sure the new namespace prefix is being used, we need to apply an additional xmlns declaration chech:
     * This is due to a particular rule in the XML serialization spec,
     * that enforces that a namespaceURI on an element is only associated with exactly one prefix.
     * See the note of bullet point 2 of https://www.w3.org/TR/DOM-Parsing/#dfn-concept-serialize-xml.
     *
     * If you rename a:xx to b:xx an xmlns:b="xx" attribute gets added at the end, but prefix a: will still be serialized.
     * So in this case, we need to remove the xmlns declaration first.
     */
    if ($newPrefix && $newPrefix !== $target->prefix && $target->hasAttribute('xmlns:'.$target->prefix)) {
        $target->removeAttribute('xmlns:'.$target->prefix);
    }

    disallow_issues(static fn () => $target->rename($newNamespaceURI, $newQName));

    return $target;
}
