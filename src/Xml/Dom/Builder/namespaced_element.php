<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMDocument;
use DOMElement;
use DOMNode;
use Webmozart\Assert\Assert;
use function Psl\Fun\pipe;
use function VeeWee\Xml\Dom\Predicate\is_document;

/**
 * @param list<callable(DOMElement): DOMElement> $configurators
 *
 * @return callable(DOMNode): DOMElement
 */
function namespaced_element(string $namespace, string $qualifiedName, callable ...$configurators): callable
{
    return static function (DOMNode $node) use ($namespace, $qualifiedName, $configurators): DOMElement {
        $document = is_document($node) ? $node : $node->ownerDocument;
        Assert::isInstanceOf($document, DOMDocument::class, 'Can not create an element without a DOM document.');

        return pipe(...$configurators)($document->createElementNS($namespace, $qualifiedName));
    };
}
