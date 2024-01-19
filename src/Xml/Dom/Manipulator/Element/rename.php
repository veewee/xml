<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Element;

use DOMAttr;
use DOMElement;
use DOMNameSpaceNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\element;
use function VeeWee\Xml\Dom\Builder\namespaced_element;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;
use function VeeWee\Xml\Dom\Locator\Element\parent_element;
use function VeeWee\Xml\Dom\Locator\Node\children;
use function VeeWee\Xml\Dom\Manipulator\append;
use function VeeWee\Xml\Dom\Predicate\is_default_xmlns_attribute;

/**
 * @throws RuntimeException
 */
function rename(DOMElement $target, string $newQName, ?string $newNamespaceURI = null): DOMElement
{
    $isRootElement = $target->ownerDocument && $target === $target->ownerDocument->documentElement;
    $parent = $isRootElement ? $target->ownerDocument : parent_element($target);
    $namespace = $newNamespaceURI ?? $target->namespaceURI;
    $builder = $namespace !== null
        ? namespaced_element($namespace, $newQName)
        : element($newQName);

    $newElement = $builder($parent);

    append(...children($target))($newElement);

    xmlns_attributes_list($target)->forEach(
        static function (DOMNameSpaceNode $attribute) use ($target, $newElement): void {
            if (is_default_xmlns_attribute($attribute) || $target->prefix === $attribute->prefix) {
                return;
            }
            xmlns_attribute($attribute->prefix, $attribute->namespaceURI)($newElement);
        }
    );

    attributes_list($target)->forEach(
        static function (DOMAttr $attribute) use ($newElement): void {
            $newElement->setAttributeNode($attribute);
        }
    );

    $parent->replaceChild($newElement, $target);

    return $newElement;
}
