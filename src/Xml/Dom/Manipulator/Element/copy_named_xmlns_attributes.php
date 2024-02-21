<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Element;

use \DOM\Element;
use \DOM\NameSpaceNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Xmlns\linked_namespaces;

/**
 * @throws RuntimeException
 */
function copy_named_xmlns_attributes(\DOM\Element $target, \DOM\Element $source): void
{
    linked_namespaces($source)->forEach(static function (\DOM\NameSpaceNode $xmlns) use ($target) {
        if ($xmlns->prefix && !$target->hasAttribute($xmlns->nodeName)) {
            xmlns_attribute($xmlns->prefix, $xmlns->namespaceURI)($target);
        }
    });
}
