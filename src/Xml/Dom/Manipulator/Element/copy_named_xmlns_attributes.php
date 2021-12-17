<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Element;

use DOMElement;
use DOMNameSpaceNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Xmlns\linked_namespaces;

/**
 * @throws RuntimeException
 */
function copy_named_xmlns_attributes(DOMElement $target, DOMElement $source): void
{
    linked_namespaces($source)->forEach(static function (DOMNameSpaceNode $xmlns) use ($target) {
        if ($xmlns->prefix && !$target->hasAttribute($xmlns->nodeName)) {
            xmlns_attribute($xmlns->prefix, $xmlns->namespaceURI)($target);
        }
    });
}
