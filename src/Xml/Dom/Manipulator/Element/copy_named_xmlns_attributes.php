<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Element;

use Dom\Attr;
use Dom\Element;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;

/**
 * @throws RuntimeException
 */
function copy_named_xmlns_attributes(Element $target, Element $source): void
{
    xmlns_attributes_list($source)->forEach(static function (Attr $xmlns) use ($target) {
        if ($xmlns->prefix !== null && !$target->hasAttribute($xmlns->nodeName)) {
            xmlns_attribute($xmlns->localName, $xmlns->value)($target);
        }
    });
}
