<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Xmlns;

use Dom\Attr;
use Dom\Element;
use DOMException;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;
use function VeeWee\Xml\Dom\Locator\Element\children;
use function VeeWee\Xml\Dom\Predicate\is_xmlns_attribute;
use const Dom\INVALID_MODIFICATION_ERR;

/**
 * @throws RuntimeException
 * @param non-empty-string $newPrefix
 */
function rename_element_namespace(Element $element, string $namespaceURI, string $newPrefix): void
{
    children($element)->forEach(
        static fn (Element $child) => rename_element_namespace($child, $namespaceURI, $newPrefix)
    );

    attributes_list($element)->forEach(static function (Attr $attr) use ($namespaceURI, $newPrefix, $element) {
        if ($attr->namespaceURI === $namespaceURI) {
            $attr->rename($namespaceURI, $newPrefix . ':' . $attr->localName);
        }

        if (is_xmlns_attribute($attr) && $attr->value === $namespaceURI) {
            try {
                $attr->rename($attr->namespaceURI, 'xmlns:' . $newPrefix);

            } catch (DOMException $e) {
                if ($e->getCode() === INVALID_MODIFICATION_ERR) {
                    // Remove the attribute that would become a duplicate
                    $element->removeAttributeNode($attr);
                } else {
                    // @codeCoverageIgnoreStart
                    throw $e;
                    // @codeCoverageIgnoreEnd
                }
            }
            $attr->rename($attr->namespaceURI, 'xmlns:' . $newPrefix);
        }
    });

    if ($element->namespaceURI === $namespaceURI) {
        $element->rename($namespaceURI, $newPrefix . ':' . $element->localName);
    }
}
