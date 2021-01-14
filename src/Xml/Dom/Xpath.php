<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use DOMXPath;
use function Psl\Fun\pipe;

final class Xpath
{
    private DOMXPath $xpath;

    private function __construct(DOMXPath $xpath)
    {
        $this->xpath = $xpath;
    }

    public static function fromDocument(Document $document, callable ... $configurators): self
    {
        return new self(
            pipe($configurators)($document->toUnsafeDocument())
        );
    }
}
