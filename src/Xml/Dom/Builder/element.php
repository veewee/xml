<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMElement;
use DOMNode;
use function VeeWee\Xml\Dom\Assert\assert_element;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(DOMElement): DOMElement> $configurators
 *
 * @return Closure(DOMNode): DOMElement
 */
function element(string $name, callable ...$configurators): Closure
{
    return static function (DOMNode $node) use ($name, $configurators): DOMElement {
        $document = detect_document($node);

        return assert_element(
            configure(...$configurators)($document->createElement($name))
        );
    };
}
