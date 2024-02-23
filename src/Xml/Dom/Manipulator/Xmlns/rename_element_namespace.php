<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Xmlns;

use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

/**
 * @throws RuntimeException
 */
function rename_element_namespace(\DOM\Element $root, string $namespaceURI, string $newPrefix): void
{
    foreach ($root->childNodes as $child) {
        if (is_element($child)) {
            rename_element_namespace($child, $namespaceURI, $newPrefix);
        }
    }

    foreach ($root->attributes as $attr) {
        match (true) {
            $attr->namespaceURI === $namespaceURI => $attr->rename($namespaceURI, $newPrefix . ':' . $attr->localName),
            is_xmlns_attribute($attr) && $attr->value === $namespaceURI => $attr->rename($attr->namespaceURI, 'xmlns:' . $newPrefix),
            default => null,
        };
    }

    if ($root->namespaceURI === $namespaceURI) {
        $root->rename($namespaceURI, $newPrefix . ':' . $root->localName);
    }
}
