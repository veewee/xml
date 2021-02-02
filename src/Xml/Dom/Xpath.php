<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use InvalidArgumentException;
use Psl\Type\Type;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Fun\pipe;
use function VeeWee\Xml\Dom\Xpath\Locator\evaluate;
use function VeeWee\Xml\Dom\Xpath\Locator\query;
use function VeeWee\Xml\Dom\Xpath\Locator\query_single;

final class Xpath
{
    private function __construct(
        private DOMXPath $xpath
    ) {
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
     * @template T
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
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function querySingle(string $expression, DOMNode $contextNode = null): DOMElement
    {
        return $this->locate(query_single($expression, $contextNode));
    }

    /**
     * @template T
     *
     * @param Type<T> $type
     *
     * @return T
     * @throws RuntimeException
     */
    public function evaluate(string $expression, Type $type, DOMNode $contextNode = null)
    {
        return $this->locate(evaluate($expression, $type, $contextNode));
    }
}
