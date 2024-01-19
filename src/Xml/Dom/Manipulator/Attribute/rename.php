<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Attribute;

use DOMAttr;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\attribute;
use function VeeWee\Xml\Dom\Builder\namespaced_attribute;
use function VeeWee\Xml\Dom\Locator\Element\parent_element;
use function VeeWee\Xml\Dom\Manipulator\Node\remove;
use function VeeWee\Xml\Dom\Predicate\is_attribute;

/**
 * @throws RuntimeException
 */
function rename(DOMAttr $target, string $newQName, ?string $newNamespaceURI = null): DOMAttr
{
    $element = parent_element($target);
    $namespace = $newNamespaceURI ?? $target->namespaceURI;
    $value = $target->nodeValue ?? '';

    $builder = $namespace !== null
        ? namespaced_attribute($namespace, $newQName, $value)
        : attribute($newQName, $value);

    remove($target);
    $builder($element);

    // If the namespace prefix of the target still exists, PHP will fallback into using that prefix.
    // In that case it is not possible to fully rename an attribute.
    // If you want to rename a prefix, you'll have to remove the xmlns first
    // or make sure the new prefix is found first for the given namespace URI.
    $result = $element->getAttributeNode($newQName);

    /** @psalm-suppress TypeDoesNotContainType - It can actually be null if the exact node name is not found. */
    if (!$result || !is_attribute($result)) {
        throw RuntimeException::withMessage(
            'Unable to rename attribute '.$target->nodeName.' into '.$newQName.'. You might need to swap xmlns prefix first!'
        );
    }

    return $result;
}
