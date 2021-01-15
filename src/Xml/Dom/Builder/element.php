<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMDocument;
use DOMElement;
use function Psl\Fun\pipe;

/**
 * @param list<callable(DOMElement): DOMElement> $configurators
 *
 * @return callable(DOMDocument): DOMElement
 */
function element(string $name, callable ...$configurators): callable {
    return static fn (DOMDocument $document): DOMElement
        => pipe(...$configurators)($document->createElement($name));
}
