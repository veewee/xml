<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use \DOM\Element;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;

/**
 * @return Closure(\DOM\Element): \DOM\Element
 */
function value(string $value): Closure
{
    return static function (\DOM\Element $node) use ($value): \DOM\Element {
        $document = detect_document($node);
        $text = $document->createTextNode($value);
        $node->appendChild($text);

        return $node;
    };
}
