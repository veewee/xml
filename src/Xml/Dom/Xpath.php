<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use DOMNode;
use DOMNodeList;
use DOMXPath;
use VeeWee\Xml\Exception\RuntimeException;
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

    /**
     * @param list<callable(DOMXPath): DOMXPath> $configurators
     */
    public static function fromDocument(Document $document, callable ... $configurators): self
    {
        return new self(
            pipe(...$configurators)(new DOMXPath($document->toUnsafeDocument()))
        );
    }

    /**
     * @teplate T
     * @param callable(DOMXpath): T $locator
     *
     * @return T
     * @throws RuntimeException
     */
    public function locate(callable $locator)
    {
        return $locator($this->xpath);
    }

    /**
     * @throws RuntimeException
     */
    public function query(string $expression, DOMNode $contextNode = null): DOMNodeList
    {
        return $this->locate(query($expression, $contextNode));
    }

    /**
     * @return mixed
     * @throws RuntimeException
     */
    public function evaluate(string $expression, DOMNode $contextNode = null)
    {
        return $this->locate(evaluate($expression, $contextNode));
    }
}
