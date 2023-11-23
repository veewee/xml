<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use Closure;
use DOMDocument;
use DOMElement;
use DOMNode;
use Webmozart\Assert\Assert;
use function VeeWee\Xml\Dom\Assert\assert_element;
use function VeeWee\Xml\Dom\Predicate\is_document;
use function VeeWee\Xml\Internal\configure;

/**
 * @param list<callable(DOMElement): DOMElement> $configurators
 *
 * @return Closure(DOMNode): DOMElement
 */
function namespaced_element(string $namespace, string $qualifiedName, callable ...$configurators): Closure
{
    return static function (DOMNode $node) use ($namespace, $qualifiedName, $configurators): DOMElement {
        $document = is_document($node) ? $node : $node->ownerDocument;
        Assert::isInstanceOf($document, DOMDocument::class, 'Can not create an element without a DOM document.');

        return assert_element(
            configure(...$configurators)($document->createElementNS($namespace, $qualifiedName))
        );
    };
}
