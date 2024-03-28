<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use \DOM\Node;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Manipulator\Attribute\rename as rename_attribute;
use function VeeWee\Xml\Dom\Manipulator\Element\rename as rename_element;
use function VeeWee\Xml\Dom\Predicate\is_attribute;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @see https://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226/DOM3-Core.html#core-Document3-renameNode
 *
 * @throws RuntimeException
 */
function rename(\DOM\Node $target, string $newQName, ?string $newNamespaceURI = null): \DOM\Node
{
    return match(true) {
        is_attribute($target) => rename_attribute($target, $newQName, $newNamespaceURI),
        is_element($target) => rename_element($target, $newQName, $newNamespaceURI),
        default => throw RuntimeException::withMessage('Can not rename dom node with type ' . get_class($target))
    };
}
