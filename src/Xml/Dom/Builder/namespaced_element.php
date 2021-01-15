<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMDocument;
use DOMElement;
use function Psl\Fun\pipe;

/**
 * @param list<callable(DOMNode): DOMNode> $configurators
 *
 * @return DOMElement
 */
function namespaced_element(string $namespace, string $name, callable ...$configurators): callable {
    return static fn (DOMDocument $document): DOMElement
        => pipe(...$configurators)($document->createElementNS($namespace, $name));
}
