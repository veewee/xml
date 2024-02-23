<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Element;

use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Builder\xmlns_attribute;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;

/**
 * @throws RuntimeException
 */
function copy_named_xmlns_attributes(\DOM\Element $target, \DOM\Element $source): void
{
    xmlns_attributes_list($source)->forEach(static function (\DOM\Attr $xmlns) use ($target) {
        if ($xmlns->prefix && !$target->hasAttribute($xmlns->nodeName)) {
            xmlns_attribute($xmlns->localName, $xmlns->value)($target);
        }
    });
}
