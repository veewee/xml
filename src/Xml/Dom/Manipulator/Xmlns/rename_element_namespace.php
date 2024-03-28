<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Xmlns;

use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;

/**
 * @throws RuntimeException
 * @param non-empty-string $newPrefix
 */
function rename_element_namespace(\DOM\Element $root, string $namespaceURI, string $newPrefix): void
{

    $recurse = function (\DOM\Element $element) use (&$recurse, $namespaceURI, $newPrefix): void {
        foreach ($element->childNodes as $child) {
            if (is_element($child)) {
                $recurse($child);
            }
        }

        $hasXmlnsAttribute = false;
        foreach ($element->attributes as $attr) {
            if ($attr->namespaceURI === $namespaceURI) {
                $attr->rename($namespaceURI, $newPrefix . ':' . $attr->localName);
            }

            if (is_xmlns_attribute($attr) && $attr->value === $namespaceURI) {
                $attr->rename($attr->namespaceURI, 'xmlns:' . $newPrefix);
            }
        }

        if ($element->namespaceURI === $namespaceURI) {
            $element->rename($namespaceURI, $newPrefix . ':' . $element->localName);
        }
    };

    $recurse(
        // TODO - should become : xmlns_attribute($newPrefix, $namespaceURI)($root),
        $root
    );
}
