<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use DOMNode;
use DOMXPath;
use function Psl\Fun\pipe;
use function VeeWee\Xml\Dom\Xpath\Locator\evaluate;
use function VeeWee\Xml\Dom\Xpath\Locator\query;

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
            pipe($configurators)(new DOMXPath($document->toUnsafeDocument()))
        );
    }

    public function locate(callable $locator)
    {
        return $locator($this->xpath);
    }

    public function query(string $expression, DOMNode $contextNode = null)
    {
        return query($expression, $contextNode)($this->xpath);
    }

    public function evaluate(string $expression, DOMNode $contextNode = null)
    {
        return evaluate($expression, $contextNode)($this->xpath);
    }
}
